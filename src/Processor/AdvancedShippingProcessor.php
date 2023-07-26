<?php

/*
 * This file is part of Monsieur Biz' Advanced Shipping plugin for Sylius.
 *
 * (c) Monsieur Biz <sylius@monsieurbiz.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace MonsieurBiz\SyliusAdvancedShippingPlugin\Processor;

use Doctrine\ORM\EntityManagerInterface;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Entity\AddressProviderAwareInterface;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Entity\AddressTemporaryAwareInterface;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Entity\AdvancedShipmentMetadataAwareInterface;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Entity\ShippingAddressProviderConfigurationInterface;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Entity\TemporaryAddressesAwareInterface;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Event\ShippingAddressFromMetadataEvent;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Factory\AddressProviderFactoryInterface;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Provider\ShippingAddressProvider\ShippingAddressProviderInterface;
use Sylius\Bundle\CoreBundle\Doctrine\ORM\AddressRepository;
use Sylius\Component\Addressing\Comparator\AddressComparatorInterface;
use Sylius\Component\Core\Model\AddressInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\ShipmentInterface;
use Sylius\Component\Shipping\Model\ShippingMethodInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

final class AdvancedShippingProcessor
{
    private AddressProviderFactoryInterface $addressProviderFactory;

    private AddressRepository $addressRepository;

    private EntityManagerInterface $orderManager;

    private EventDispatcherInterface $eventDispatcher;

    private AddressComparatorInterface $addressComparator;

    public function __construct(
        AddressProviderFactoryInterface $addressProviderFactory,
        AddressRepository $addressRepository,
        EntityManagerInterface $orderManager,
        EventDispatcherInterface $eventDispatcher,
        AddressComparatorInterface $addressComparator
    ) {
        $this->addressProviderFactory = $addressProviderFactory;
        $this->addressRepository = $addressRepository;
        $this->orderManager = $orderManager;
        $this->eventDispatcher = $eventDispatcher;
        $this->addressComparator = $addressComparator;
    }

    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function process(OrderInterface $order): void
    {
        $shipments = $order->getShipments();
        $shippingAddress = $order->getShippingAddress();
        if (null === $shippingAddress) {
            return;
        }

        /** @var ShipmentInterface $shipment */
        foreach ($shipments as $shipment) {
            if (!$shipment instanceof AdvancedShipmentMetadataAwareInterface || null === $shipment->getMethod()) {
                continue;
            }

            $shippingAddress = $this->processAddressProvider($shippingAddress, $shipment, $shipment->getMethod(), $order);
            $order->setShippingAddress($shippingAddress);
        }
    }

    public function getOriginalAddress(AdvancedShipmentMetadataAwareInterface $shipment): ?AddressInterface
    {
        if (false === $shipment->hasProviderMetadata('address_provider', 'original_address_id')) {
            return null;
        }

        $originalAddressId = (int) $shipment->getProviderMetadata('address_provider', 'original_address_id');

        /** @phpstan-ignore-next-line */
        return $this->addressRepository->find($originalAddressId);
    }

    private function processAddressProvider(AddressInterface $shippingAddress, AdvancedShipmentMetadataAwareInterface $shipment, ShippingMethodInterface $method, OrderInterface $order): AddressInterface
    {
        if (false === $this->isAddressProvidedMethod($method)) {
            if (false !== $shipment->hasProviderMetadata('address_provider', 'original_address_id')) {
                $originalAddressId = (int) $shipment->getProviderMetadata('address_provider', 'original_address_id');
                /** @var ?AddressInterface $currentShippingAddress */
                $currentShippingAddress = $this->addressRepository->find($originalAddressId);

                $this->removeOldShippingAddress($originalAddressId, $currentShippingAddress, $order);
                $shipment->setProviderMetadata('address_provider', 'original_address_id', null);
            }

            return $currentShippingAddress ?? $shippingAddress;
        }

        $metadata = $this->getMetadata($shipment, $method);
        /** @var AddressProviderAwareInterface $method */
        /** @var ShippingAddressProviderConfigurationInterface $configuration */
        $configuration = $method->getShippingAddressProviderConfiguration();
        $provider = $this->addressProviderFactory->createFromConfiguration($configuration);

        if (false === $shipment->hasProviderMetadata('address_provider', 'original_address_id')) {
            $shipment->setProviderMetadata('address_provider', 'original_address_id', (string) $shippingAddress->getId());
        }

        $originalAddressId = (int) $shipment->getProviderMetadata('address_provider', 'original_address_id');
        $currentShippingAddress = $this->getAddressFromMetadata($provider, $metadata, $order);
        $this->eventDispatcher->dispatch(
            new ShippingAddressFromMetadataEvent($currentShippingAddress, $shippingAddress),
            'monsieurbiz.advanced_shipping.after_shipping_address_from_metadata'
        );
        $currentShippingAddress = $this->getExistingOrCurrentShippingAddress($currentShippingAddress, $order);

        $this->removeOldShippingAddress($originalAddressId, $currentShippingAddress, $order);

        return $currentShippingAddress ?? $shippingAddress;
    }

    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    private function removeOldShippingAddress(int $originalAddressId, ?AddressInterface $currentShippingAddress, OrderInterface $order): void
    {
        if (null === $currentShippingAddress || !$order instanceof TemporaryAddressesAwareInterface) {
            return;
        }

        foreach ($order->getTemporaryAddresses() as $temporaryAddress) {
            // Remove old temporary addresses if it is not the current one and not the original address.
            if ($this->canRemoveTemporaryAddress($currentShippingAddress, $temporaryAddress, $originalAddressId)) {
                $this->orderManager->getUnitOfWork()->scheduleOrphanRemoval($temporaryAddress);
            }
        }
    }

    private function isAddressProvidedMethod(ShippingMethodInterface $method): bool
    {
        return $method instanceof AddressProviderAwareInterface
            && null !== $method->getShippingAddressProviderConfiguration()
            && null !== $method->getCode();
    }

    private function getMetadata(AdvancedShipmentMetadataAwareInterface $shipment, ShippingMethodInterface $method): ?array
    {
        return
            null !== $method->getCode() && true === $shipment->hasMethodMetadata($method->getCode(), 'addressProvider') ?
            $shipment->getMethodMetadata($method->getCode(), 'addressProvider')
            : null;
    }

    private function getAddressFromMetadata(ShippingAddressProviderInterface $provider, ?array $metadata, OrderInterface $order): ?AddressInterface
    {
        $currentShippingAddress = $provider->getShippingAddressFromMetadata($metadata);
        if ($currentShippingAddress instanceof AddressTemporaryAwareInterface) {
            $currentShippingAddress->setSourceOrder($order);
        }

        return $currentShippingAddress;
    }

    private function canRemoveTemporaryAddress(AddressInterface $address, AddressInterface $temporaryAddress, int $originalAddressId): bool
    {
        return $address->getId() !== $temporaryAddress->getId() && $originalAddressId !== $temporaryAddress->getId();
    }

    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    private function getExistingOrCurrentShippingAddress(?AddressInterface $address, OrderInterface $order): ?AddressInterface
    {
        if (null === $address || !$order instanceof TemporaryAddressesAwareInterface) {
            return $address;
        }

        // Check if the address is already in the temporary addresses.
        foreach ($order->getTemporaryAddresses() as $temporaryAddress) {
            if ($this->addressComparator->equal($address, $temporaryAddress)) {
                return $temporaryAddress;
            }
        }

        return $address;
    }
}

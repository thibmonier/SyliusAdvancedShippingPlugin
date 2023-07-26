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

namespace MonsieurBiz\SyliusAdvancedShippingPlugin\Decorator;

use MonsieurBiz\SyliusAdvancedShippingPlugin\Entity\AddressProviderAwareInterface;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Entity\AdvancedShipmentMetadataAwareInterface;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Factory\AddressProviderFactoryInterface;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Provider\ShippingAddressProvider\ShippingAddressProviderInterface;
use Sylius\Component\Core\Customer\OrderAddressesSaverInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\ShipmentInterface;

final class CustomerOrderAddressesSaverDecorator implements OrderAddressesSaverInterface
{
    private AddressProviderFactoryInterface $addressProviderFactory;

    private OrderAddressesSaverInterface $decoratedService;

    public function __construct(
        OrderAddressesSaverInterface $decoratedService,
        AddressProviderFactoryInterface $addressProviderFactory
    ) {
        $this->decoratedService = $decoratedService;
        $this->addressProviderFactory = $addressProviderFactory;
    }

    public function saveAddresses(OrderInterface $order): void
    {
        $shippingAddress = $order->getShippingAddress();
        if ($this->hasOrderTemporaryShippingAddress($order)) {
            // "Disable" the shipping address temporary
            $order->setShippingAddress(null);
        }

        $this->decoratedService->saveAddresses($order);

        // If the shipping address is temporary "disabled":
        if (null === $order->getShippingAddress() && null !== $shippingAddress) {
            $order->setShippingAddress($shippingAddress);
        }
    }

    private function hasOrderTemporaryShippingAddress(OrderInterface $order): bool
    {
        $shippingAddress = $order->getShippingAddress();
        if (null === $shippingAddress) {
            return false;
        }

        $shipments = $order->getShipments();
        foreach ($shipments as $shipment) {
            if ($this->hasShipmentTemporaryShippingAddress($shipment)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    private function hasShipmentTemporaryShippingAddress(ShipmentInterface $shipment): bool
    {
        if (!$shipment instanceof AdvancedShipmentMetadataAwareInterface || null === $shipment->getMethod()) {
            return false;
        }

        $provider = $this->getProvider($shipment);

        return null !== $provider && true === $provider->isTemporaryAddress();
    }

    private function getProvider(ShipmentInterface $shipment): ?ShippingAddressProviderInterface
    {
        $method = $shipment->getMethod();
        if (!$method instanceof AddressProviderAwareInterface) {
            return null;
        }

        $configuration = $method->getShippingAddressProviderConfiguration();

        return null === $configuration ? null : $this->addressProviderFactory->createFromConfiguration($configuration);
    }
}

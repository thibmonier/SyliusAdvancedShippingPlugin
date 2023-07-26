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

namespace MonsieurBiz\SyliusAdvancedShippingPlugin\Provider\ShippingAddressProvider;

use MonsieurBiz\SyliusAdvancedShippingPlugin\Api\ChronopostPickup\ClientFactoryInterface;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Api\ChronopostPickup\ClientInterface;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Api\ChronopostPickup\Config\ChronopostPickupConfigInterface;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Entity\AddressPickupPointAwareInterface;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Entity\AddressTemporaryAwareInterface;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Exception\MissingProviderConfigurationException;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Form\Type\AddressProvider\ChronopostPickupAddressProviderMetadataType;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Model\PickupPointInterface;
use Sylius\Component\Core\Model\AddressInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

final class ChronopostPickupShippingAddressProvider extends AbstractPickupShippingAddressProvider implements ShippingAddressProviderInterface
{
    public const TYPE = 'chronopost_pickup';

    private const FORM_TEMPLATE = '@MonsieurBizSyliusAdvancedShippingPlugin/Shop/Checkout/SelectShipping/Shipment/AddressProvider/_pickupPointAddressProvider.html.twig';

    private ClientFactoryInterface $clientFactory;

    private FactoryInterface $addressFactory;

    public function __construct(
        ClientFactoryInterface $clientFactory,
        FactoryInterface $addressFactory
    ) {
        $this->clientFactory = $clientFactory;
        $this->addressFactory = $addressFactory;
    }

    public function getShipmentMetadataFormType(): ?string
    {
        return ChronopostPickupAddressProviderMetadataType::class;
    }

    public function getType(): string
    {
        return self::TYPE;
    }

    public function getShipmentMetadataTemplate(): ?string
    {
        return self::FORM_TEMPLATE;
    }

    public function isTemporaryAddress(): bool
    {
        return true;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function getShippingAddressFromMetadata(?array $metadata): ?AddressInterface
    {
        if (true === empty($metadata['pickupPoint'])) {
            return null;
        }

        /** @var PickupPointInterface $pickupPoint */
        $pickupPoint = $metadata['pickupPoint'];
        /** @var AddressInterface $address */
        $address = $this->addressFactory->createNew();
        $address->setFirstName($metadata['firstName'] ?? '');
        $address->setLastName($metadata['lastName'] ?? '');
        $address->setCompany($pickupPoint->getName());
        $address->setStreet($pickupPoint->getAddress1());
        $address->setPostcode($pickupPoint->getPostcode());
        $address->setCity($pickupPoint->getCity());
        $address->setCountryCode($pickupPoint->getCountryCode());
        if ($address instanceof AddressTemporaryAwareInterface) {
            $address->setTemporary($this->isTemporaryAddress());
        }
        if ($address instanceof AddressPickupPointAwareInterface) {
            $address->setPickupPointType(self::TYPE);
            $address->setPickupPointCode($pickupPoint->getIdentifier());
        }

        return $address;
    }

    protected function getClient(): ClientInterface
    {
        /** @var ChronopostPickupConfigInterface|null $configuration */
        $configuration = $this->getConfiguration()['apiConfiguration'] ?? null;
        if (null === $configuration) {
            throw new MissingProviderConfigurationException('Chronopost Pickup client configuration is missing');
        }

        return $this->clientFactory->create($configuration);
    }
}

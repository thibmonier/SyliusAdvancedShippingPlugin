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

use MonsieurBiz\SyliusAdvancedShippingPlugin\Api\DpdPickup\ClientFactoryInterface;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Api\DpdPickup\ClientInterface;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Api\DpdPickup\Config\DpdPickupConfigInterface;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Entity\AddressPickupPointAwareInterface;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Entity\AddressTemporaryAwareInterface;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Exception\MissingProviderConfigurationException;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Form\Type\AddressProvider\DpdPickupAddressProviderMetadataType;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Model\PickupPointInterface;
use Sylius\Component\Core\Model\AddressInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

final class DpdPickupShippingAddressProvider extends AbstractPickupShippingAddressProvider implements ShippingAddressProviderInterface
{
    public const TYPE = 'dpd_pickup';

    private const FORM_TEMPLATE = '@MonsieurBizSyliusAdvancedShippingPlugin/Shop/Checkout/SelectShipping/Shipment/AddressProvider/_pickupPointAddressProvider.html.twig';

    private PropertyAccessor $accessor;

    private ClientFactoryInterface $clientFactory;

    private FactoryInterface $addressFactory;

    public function __construct(
        ClientFactoryInterface $clientFactory,
        FactoryInterface $addressFactory
    ) {
        $this->accessor = PropertyAccess::createPropertyAccessor();
        $this->clientFactory = $clientFactory;
        $this->addressFactory = $addressFactory;
    }

    public function getShipmentMetadataFormType(): ?string
    {
        return DpdPickupAddressProviderMetadataType::class;
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

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function getAddressListFromMetadata(array $metadata): array
    {
        if (true === empty($metadata['postcode'])) {
            return [];
        }
        $client = $this->getClient();

        return $client->getPickupPointsByPostcode($metadata['postcode']);
    }

    protected function getClient(): ClientInterface
    {
        /** @var DpdPickupConfigInterface|null $configuration */
        $configuration = $this->getConfiguration()['apiConfiguration'] ?? null;
        if (null === $configuration) {
            throw new MissingProviderConfigurationException('DPD Pickup client configuration is missing');
        }

        return $this->clientFactory->create($configuration);
    }
}

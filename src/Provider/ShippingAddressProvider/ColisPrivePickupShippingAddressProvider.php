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

use MonsieurBiz\SyliusAdvancedShippingPlugin\Api\ColisPrivePickup\ClientFactoryInterface;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Api\ColisPrivePickup\ClientInterface;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Api\ColisPrivePickup\Config\ColisPrivePickupConfigInterface;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Entity\AddressPickupPointAwareInterface;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Entity\AddressTemporaryAwareInterface;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Exception\MissingProviderConfigurationException;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Form\Type\AddressProvider\ColisPrivePickupProviderMetadataType;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Model\PickupPointInterface;
use Sylius\Component\Core\Model\AddressInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

class ColisPrivePickupShippingAddressProvider extends AbstractPickupShippingAddressProvider implements ShippingAddressProviderInterface
{
    public const TYPE = 'colis_prive_pickup';

    private const FORM_TEMPLATE = '@MonsieurBizSyliusAdvancedShippingPlugin/Shop/Checkout/SelectShipping/Shipment/AddressProvider/_pickupPointAddressProvider.html.twig';

    public function __construct(
        private ClientFactoryInterface $clientFactory,
        private FactoryInterface $addressFactory
    ) {
    }

    public function getType(): string
    {
        return self::TYPE;
    }

    public function getShipmentMetadataFormType(): ?string
    {
        return ColisPrivePickupProviderMetadataType::class;
    }

    public function getShipmentMetadataTemplate(): ?string
    {
        return self::FORM_TEMPLATE;
    }

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
        /** @var ColisPrivePickupConfigInterface|null $configuration */
        $configuration = $this->getConfiguration()['apiConfiguration'] ?? null;
        if (null === $configuration) {
            throw new MissingProviderConfigurationException('Colis Privé Pickup client configuration is missing');
        }

        return $this->clientFactory->create($configuration);
    }

    public function isTemporaryAddress(): bool
    {
        return true;
    }
}

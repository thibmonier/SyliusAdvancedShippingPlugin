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

use MonsieurBiz\SyliusAdvancedShippingPlugin\Api\MondialRelay\ClientFactoryInterface;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Api\MondialRelay\ClientInterface;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Api\MondialRelay\Config\MondialRelayConfigInterface;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Entity\AddressPickupPointAwareInterface;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Entity\AddressTemporaryAwareInterface;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Exception\MissingProviderConfigurationException;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Form\Type\AddressProvider\MondialRelayAddressProviderMetadataType;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Model\PickupPointInterface;
use Sylius\Component\Core\Model\AddressInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

final class MondialRelayShippingAddressProvider extends AbstractPickupShippingAddressProvider implements ShippingAddressProviderInterface
{
    public const TYPE = 'mondial_relay';

    private const FORM_TEMPLATE = '@MonsieurBizSyliusAdvancedShippingPlugin/Shop/Checkout/SelectShipping/Shipment/AddressProvider/_pickupPointAddressProvider.html.twig';

    private PropertyAccessor $accessor;

    public function __construct(
        private ClientFactoryInterface $clientFactory,
        private FactoryInterface $addressFactory
    ) {
        $this->accessor = PropertyAccess::createPropertyAccessor();
    }

    public function getShipmentMetadataFormType(): ?string
    {
        return MondialRelayAddressProviderMetadataType::class;
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
        /** @var MondialRelayConfigInterface|null $configuration */
        $configuration = $this->getConfiguration()['apiConfiguration'] ?? null;
        if (null === $configuration) {
            throw new MissingProviderConfigurationException('Mondial Relay client configuration is missing');
        }

        return $this->clientFactory->create($configuration);
    }
}

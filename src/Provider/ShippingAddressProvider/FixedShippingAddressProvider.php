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

use MonsieurBiz\SyliusAdvancedShippingPlugin\Entity\AddressPickupPointAwareInterface;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Entity\AddressTemporaryAwareInterface;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Form\Type\AddressProvider\FixedAddressProviderMetadataType;
use Sylius\Component\Core\Model\AddressInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\PropertyAccess\Exception\AccessException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

final class FixedShippingAddressProvider extends AbstractShippingAddressProvider implements ShippingAddressProviderInterface
{
    public const TYPE = 'fixed';

    private const FORM_TEMPLATE = '@MonsieurBizSyliusAdvancedShippingPlugin/Shop/Checkout/SelectShipping/Shipment/AddressProvider/_fixed.html.twig';

    private PropertyAccessor $accessor;

    private FactoryInterface $addressFactory;

    public function __construct(FactoryInterface $addressFactory)
    {
        $this->accessor = PropertyAccess::createPropertyAccessor();
        $this->addressFactory = $addressFactory;
    }

    public function getShipmentMetadataFormType(): ?string
    {
        return FixedAddressProviderMetadataType::class;
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
     * @SuppressWarnings(PHPMD.UnusedFormalParameter) - $metadata is not used for fixed address
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function getShippingAddressFromMetadata(?array $metadata): ?AddressInterface
    {
        /** @var AddressInterface $address */
        $address = $this->addressFactory->createNew();
        $configuration = $this->getConfiguration();
        $code = $configuration['code'];
        unset($configuration['code']);

        foreach ($configuration as $propertyPath => $value) {
            try {
                $this->accessor->setValue($address, $propertyPath, $value);
            } catch (AccessException $e) {
                continue;
            }
        }
        $address->setFirstName($metadata['firstName'] ?? '');
        $address->setLastName($metadata['lastName'] ?? '');

        if ($address instanceof AddressTemporaryAwareInterface) {
            $address->setTemporary($this->isTemporaryAddress());
        }

        if ($address instanceof AddressPickupPointAwareInterface) {
            $address->setPickupPointType(self::TYPE);
            $address->setPickupPointCode($code);
        }

        return $address;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function getAddressListFromMetadata(array $metadata): array
    {
        return [];
    }
}

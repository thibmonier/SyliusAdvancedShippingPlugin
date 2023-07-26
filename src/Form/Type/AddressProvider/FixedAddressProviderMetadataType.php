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

namespace MonsieurBiz\SyliusAdvancedShippingPlugin\Form\Type\AddressProvider;

use MonsieurBiz\SyliusAdvancedShippingPlugin\Form\Type\AdvancedShippingMetadata\AbstractAdvancedShippingMetadataType;
use Sylius\Component\Core\Model\ShipmentInterface;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;

final class FixedAddressProviderMetadataType extends AbstractAdvancedShippingMetadataType
{
    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $firstName = $lastName = null;
        if (true === isset($options['shipment'])) {
            /** @var ShipmentInterface $shipment */
            $shipment = $options['shipment'];
            if (null !== $shipment->getOrder() && null !== $shipment->getOrder()->getBillingAddress()) {
                /** @TODO maybe retrieve the default shipping address data */
                $firstName = $shipment->getOrder()->getBillingAddress()->getFirstName();
                $lastName = $shipment->getOrder()->getBillingAddress()->getLastName();
            }
        }

        $builder
            ->add('firstName', HiddenType::class, [
                'label' => false,
                'data' => $firstName,
            ])
            ->add('lastName', HiddenType::class, [
                'label' => false,
                'data' => $lastName,
            ])
        ;
    }
}

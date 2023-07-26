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
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ChronopostPickupAddressProviderMetadataType extends AbstractAdvancedShippingMetadataType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);
        $resolver->setDefault('provider_validation_group', 'provider_chronopost_pickup');
        $resolver->setDefault('identifier', 'chronopost_pickup');
    }

    public function getParent()
    {
        return PickupPointProviderMetadataType::class;
    }
}

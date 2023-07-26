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

final class ColisPrivePickupProviderMetadataType extends AbstractAdvancedShippingMetadataType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);
        $resolver->setDefault('provider_validation_group', 'provider_colis_prive_pickup');
        $resolver->setDefault('identifier', 'colis_prive');
    }

    public function getParent()
    {
        return PickupPointProviderMetadataType::class;
    }
}

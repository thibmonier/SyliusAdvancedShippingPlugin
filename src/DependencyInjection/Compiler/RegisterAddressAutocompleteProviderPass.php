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

namespace MonsieurBiz\SyliusAdvancedShippingPlugin\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class RegisterAddressAutocompleteProviderPass extends AbstractRegisterPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $this->register(
            $container,
            'monsieurbiz.advanced_shipping.registry.address_autocomplete_provider',
            'monsieurbiz.advanced_shipping.form_registry.address_autocomplete_provider',
            'monsieurbiz.advanced_shipping.address_autocomplete_provider',
            'monsieurbiz.advanced_shipping.address_autocomplete_providers'
        );
    }
}

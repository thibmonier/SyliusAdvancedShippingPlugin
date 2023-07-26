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

namespace MonsieurBiz\SyliusAdvancedShippingPlugin\Factory;

use MonsieurBiz\SyliusAdvancedShippingPlugin\Entity\ShippingAddressProviderConfigurationInterface;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Provider\ShippingAddressProvider\ShippingAddressProviderInterface;
use Sylius\Component\Registry\ServiceRegistryInterface;

final class AddressProviderFactory implements AddressProviderFactoryInterface
{
    private ServiceRegistryInterface $shippingAddressProviderRegistry;

    public function __construct(ServiceRegistryInterface $shippingAddressProviderRegistry)
    {
        $this->shippingAddressProviderRegistry = $shippingAddressProviderRegistry;
    }

    public function createFromConfiguration(ShippingAddressProviderConfigurationInterface $configuration): ShippingAddressProviderInterface
    {
        /** @var ShippingAddressProviderInterface $provider */
        $provider = $this->shippingAddressProviderRegistry->get($configuration->getProvider());
        $provider->setConfiguration($configuration->getConfiguration());

        return $provider;
    }
}

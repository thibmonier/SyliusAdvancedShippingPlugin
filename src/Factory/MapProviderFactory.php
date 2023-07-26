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

use MonsieurBiz\SyliusAdvancedShippingPlugin\Entity\MapProviderConfigurationInterface;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Provider\MapProvider\MapProviderInterface;
use Sylius\Component\Registry\ServiceRegistryInterface;

final class MapProviderFactory implements MapProviderFactoryInterface
{
    public function __construct(private ServiceRegistryInterface $mapProviderRegistry)
    {
    }

    public function createFromConfiguration(MapProviderConfigurationInterface $configuration): MapProviderInterface
    {
        /** @var MapProviderInterface $provider */
        $provider = $this->mapProviderRegistry->get($configuration->getProvider());
        $provider->setConfiguration($configuration->getConfiguration());

        return $provider;
    }
}

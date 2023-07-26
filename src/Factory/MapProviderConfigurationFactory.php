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
use Sylius\Component\Resource\Factory\FactoryInterface;

final class MapProviderConfigurationFactory implements MapProviderConfigurationFactoryInterface
{
    private FactoryInterface $decoratedFactory;

    public function __construct(FactoryInterface $decoratedFactory)
    {
        $this->decoratedFactory = $decoratedFactory;
    }

    public function createNew(): MapProviderConfigurationInterface
    {
        /** @phpstan-ignore-next-line */
        return $this->decoratedFactory->createNew();
    }

    public function createWithProviderName(string $providerName): MapProviderConfigurationInterface
    {
        /** @var MapProviderConfigurationInterface $mapProviderConfiguration */
        $mapProviderConfiguration = $this->decoratedFactory->createNew();
        $mapProviderConfiguration->setProvider($providerName);

        return $mapProviderConfiguration;
    }
}

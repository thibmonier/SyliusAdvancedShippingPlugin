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
use Sylius\Component\Resource\Factory\FactoryInterface;

final class AddressProviderConfigurationFactory implements AddressProviderConfigurationFactoryInterface
{
    private FactoryInterface $decoratedFactory;

    public function __construct(FactoryInterface $decoratedFactory)
    {
        $this->decoratedFactory = $decoratedFactory;
    }

    public function createNew(): ShippingAddressProviderConfigurationInterface
    {
        /** @phpstan-ignore-next-line */
        return $this->decoratedFactory->createNew();
    }

    public function createWithAddressProvider(string $providerName): ShippingAddressProviderConfigurationInterface
    {
        /** @var ShippingAddressProviderConfigurationInterface $shippingAddressProviderConfiguration */
        $shippingAddressProviderConfiguration = $this->decoratedFactory->createNew();
        $shippingAddressProviderConfiguration->setProvider($providerName);

        return $shippingAddressProviderConfiguration;
    }
}

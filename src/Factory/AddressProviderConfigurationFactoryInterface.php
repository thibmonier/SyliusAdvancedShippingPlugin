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

interface AddressProviderConfigurationFactoryInterface extends FactoryInterface
{
    public function createWithAddressProvider(string $providerName): ShippingAddressProviderConfigurationInterface;
}

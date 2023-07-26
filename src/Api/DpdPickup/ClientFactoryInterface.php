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

namespace MonsieurBiz\SyliusAdvancedShippingPlugin\Api\DpdPickup;

use MonsieurBiz\SyliusAdvancedShippingPlugin\Api\DpdPickup\Config\DpdPickupConfigInterface;

interface ClientFactoryInterface
{
    public function create(DpdPickupConfigInterface $config): ClientInterface;
}

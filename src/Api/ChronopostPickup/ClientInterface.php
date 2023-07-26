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

namespace MonsieurBiz\SyliusAdvancedShippingPlugin\Api\ChronopostPickup;

use MonsieurBiz\SyliusAdvancedShippingPlugin\Api\ChronopostPickup\Config\ChronopostPickupConfigInterface;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Api\ChronopostPickup\Model\PickupPointListQueryInterface;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Api\PickupPointClientInterface;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Model\PickupPointInterface;

interface ClientInterface extends PickupPointClientInterface
{
    public static function create(ChronopostPickupConfigInterface $config): self;

    public static function validateConfig(ChronopostPickupConfigInterface $config): void;

    /**
     * @return PickupPointInterface[]
     */
    public function getPickupPoints(string $service, PickupPointListQueryInterface $query): array;
}

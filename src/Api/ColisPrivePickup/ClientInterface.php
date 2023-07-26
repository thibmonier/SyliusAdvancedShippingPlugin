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

/*
 * This file is part of terravita corporate website.
 *
 * (c) terravita <sylius+terravita@monsieurbiz.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MonsieurBiz\SyliusAdvancedShippingPlugin\Api\ColisPrivePickup;

use MonsieurBiz\SyliusAdvancedShippingPlugin\Api\ColisPrivePickup\Config\ColisPrivePickupConfigInterface;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Api\ColisPrivePickup\Model\PickupPointListQueryInterface;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Api\PickupPointClientInterface;

interface ClientInterface extends PickupPointClientInterface
{
    public static function create(ColisPrivePickupConfigInterface $config): self;

    public static function validateConfig(ColisPrivePickupConfigInterface $config): void;

    public function getPickupPoints(PickupPointListQueryInterface $query): array;
}

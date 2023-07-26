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

namespace MonsieurBiz\SyliusAdvancedShippingPlugin\Api\ChronopostPickup\Config;

final class ChronopostPickupConfig implements ChronopostPickupConfigInterface
{
    private string $pickupApiUrl;

    private string $pickupApiAccountNumber;

    private string $pickupApiPassword;

    public function getPickupApiUrl(): string
    {
        return $this->pickupApiUrl;
    }

    public function setPickupApiUrl(string $pickupApiUrl): void
    {
        $this->pickupApiUrl = $pickupApiUrl;
    }

    public function getPickupApiAccountNumber(): string
    {
        return $this->pickupApiAccountNumber;
    }

    public function setPickupApiAccountNumber(string $pickupApiAccountNumber): void
    {
        $this->pickupApiAccountNumber = $pickupApiAccountNumber;
    }

    public function getPickupApiPassword(): string
    {
        return $this->pickupApiPassword;
    }

    public function setPickupApiPassword(string $pickupApiPassword): void
    {
        $this->pickupApiPassword = $pickupApiPassword;
    }
}

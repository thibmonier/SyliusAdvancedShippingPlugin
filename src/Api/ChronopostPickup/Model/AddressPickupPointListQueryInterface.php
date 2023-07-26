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

namespace MonsieurBiz\SyliusAdvancedShippingPlugin\Api\ChronopostPickup\Model;

interface AddressPickupPointListQueryInterface extends PickupPointListQueryInterface
{
    public function getAddress(): ?string;

    public function setAddress(?string $address): void;

    public function getZipCode(): ?string;

    public function setZipCode(?string $zipCode): void;

    public function getCity(): ?string;

    public function setCity(?string $city): void;
}

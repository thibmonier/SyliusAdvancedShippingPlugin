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

interface PickupPointListQueryInterface
{
    public const DEFAULT_TYPE = 'P';

    public const DEFAULT_SERVICE = 'L';

    public const DEFAULT_WEIGHT = '0';

    public const DEFAULT_MAX_POINT_CHRONOPOST = '10';

    public const DEFAULT_MAX_DISTANCE_SEARCH = '40';

    public const DEFAULT_HOLIDAY_TOLERANT = '1';

    public function getAccountNumber(): ?string;

    public function setAccountNumber(?string $accountNumber): void;

    public function getPassword(): ?string;

    public function setPassword(?string $password): void;

    public function getCountryCode(): ?string;

    public function setCountryCode(?string $countryCode): void;

    public function getType(): ?string;

    public function setType(?string $type): void;

    public function getProductCode(): ?string;

    public function setProductCode(?string $productCode): void;

    public function getService(): ?string;

    public function setService(?string $service): void;

    public function getWeight(): ?string;

    public function setWeight(?string $weight): void;

    public function getShippingDate(): ?string;

    public function setShippingDate(?string $shippingDate): void;

    public function getMaxPointChronopost(): ?string;

    public function setMaxPointChronopost(?string $maxPointChronopost): void;

    public function getMaxDistanceSearch(): ?string;

    public function setMaxDistanceSearch(?string $maxDistanceSearch): void;

    public function getHolidayTolerant(): ?string;

    public function setHolidayTolerant(?string $holidayTolerant): void;
}

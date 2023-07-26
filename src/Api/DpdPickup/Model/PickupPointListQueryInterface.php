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

namespace MonsieurBiz\SyliusAdvancedShippingPlugin\Api\DpdPickup\Model;

interface PickupPointListQueryInterface
{
    public const DEFAULT_CARRIER = 'EXA';

    public const DEFAULT_KEY = 'deecd7bc81b71fcc0e292b53e826c48f';

    public const DEFAULT_REQUEST_ID = '123';

    public function getCarrier(): string;

    public function setCarrier(string $carrier): void;

    public function getKey(): string;

    public function setKey(string $key): void;

    public function getZipCode(): string;

    public function setZipCode(string $zipCode): void;

    public function getRequestId(): string;

    public function setRequestId(string $requestId): void;

    public function getDateFrom(): \DateTimeInterface;

    public function setDateFrom(\DateTimeInterface $dateFrom): void;

    public function getAddress(): ?string;

    public function setAddress(?string $address): void;

    public function getCity(): ?string;

    public function setCity(?string $city): void;

    public function getCountryCode(): ?string;

    public function setCountryCode(?string $countryCode): void;

    public function getMaxPudoNumber(): ?int;

    public function setMaxPudoNumber(?int $maxPudoNumber): void;

    public function getMaxDistanceSearch(): ?int;

    public function setMaxDistanceSearch(?int $maxDistanceSearch): void;

    public function getWeight(): ?float;

    public function setWeight(?float $weight): void;

    public function getCategory(): ?string;

    public function setCategory(?string $category): void;

    public function getHolidayTolerant(): ?bool;

    public function setHolidayTolerant(?bool $holidayTolerant): void;
}

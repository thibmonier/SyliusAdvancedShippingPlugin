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

namespace MonsieurBiz\SyliusAdvancedShippingPlugin\Model;

interface PickupPointInterface
{
    public function setIdentifier(string $identifier): void;

    public function getIdentifier(): ?string;

    public function setName(string $name): void;

    public function getName(): ?string;

    public function setAddress1(string $address1): void;

    public function getAddress1(): ?string;

    public function setAddress2(string $address2): void;

    public function getAddress2(): ?string;

    public function setAddress3(string $address3): void;

    public function getAddress3(): ?string;

    public function setCountryCode(string $countryCode): void;

    public function getCountryCode(): ?string;

    public function setPostcode(string $postcode): void;

    public function getPostcode(): ?string;

    public function setCity(string $city): void;

    public function getCity(): ?string;

    public function setLocalHint(string $localHint): void;

    public function getLocalHint(): ?string;

    public function setDistance(int $distance): void;

    public function getDistance(): ?int;

    public function setLatitude(float $latitude): void;

    public function getLatitude(): ?float;

    public function setLongitude(float $longitude): void;

    public function getLongitude(): ?float;

    /**
     * @param OpeningDayInterface[] $openingDays
     */
    public function setOpeningsDays(array $openingDays): void;

    /**
     * @return ?OpeningDayInterface[]
     */
    public function getOpeningDays(): ?array;

    /**
     * @param HolidayTimeSlot[] $holidayItems
     */
    public function setHolidayItems(array $holidayItems): void;

    /**
     * @return ?HolidayTimeSlot[]
     */
    public function getHolidayItems(): ?array;
}

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

class PickupPoint implements PickupPointInterface, \JsonSerializable
{
    private ?string $identifier = null;

    private ?string $name = null;

    private ?string $address1 = null;

    private ?string $address2 = null;

    private ?string $address3 = null;

    private ?string $countryCode = null;

    private ?string $postcode = null;

    private ?string $city = null;

    private ?string $localHint = null;

    private ?int $distance = null;

    private ?float $latitude = null;

    private ?float $longitude = null;

    private ?array $openingDays = null;

    private ?array $holidayItems = null;

    public function setIdentifier(string $identifier): void
    {
        $this->identifier = $identifier;
    }

    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setAddress1(string $address1): void
    {
        $this->address1 = $address1;
    }

    public function getAddress1(): ?string
    {
        return $this->address1;
    }

    public function setAddress2(string $address2): void
    {
        $this->address2 = $address2;
    }

    public function getAddress2(): ?string
    {
        return $this->address2;
    }

    public function setAddress3(string $address3): void
    {
        $this->address3 = $address3;
    }

    public function getAddress3(): ?string
    {
        return $this->address3;
    }

    public function setCountryCode(string $countryCode): void
    {
        $this->countryCode = $countryCode;
    }

    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    public function setPostcode(string $postcode): void
    {
        $this->postcode = $postcode;
    }

    public function getPostcode(): ?string
    {
        return $this->postcode;
    }

    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setLocalHint(string $localHint): void
    {
        $this->localHint = $localHint;
    }

    public function getLocalHint(): ?string
    {
        return $this->localHint;
    }

    public function setDistance(int $distance): void
    {
        $this->distance = $distance;
    }

    public function getDistance(): ?int
    {
        return $this->distance;
    }

    public function setLatitude(float $latitude): void
    {
        $this->latitude = $latitude;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLongitude(float $longitude): void
    {
        $this->longitude = $longitude;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setOpeningsDays(array $openingDays): void
    {
        $this->openingDays = $openingDays;
    }

    public function getOpeningDays(): ?array
    {
        return $this->openingDays;
    }

    public function setHolidayItems(array $holidayItems): void
    {
        $this->holidayItems = $holidayItems;
    }

    public function getHolidayItems(): ?array
    {
        return $this->holidayItems;
    }

    /**
     * @return mixed
     */
    public function jsonSerialize()
    {
        return [
            'identifier' => $this->identifier,
            'name' => $this->name,
            'address1' => $this->address1,
            'address2' => $this->address2,
            'address3' => $this->address3,
            'countryCode' => $this->countryCode,
            'postcode' => $this->postcode,
            'city' => $this->city,
            'localHint' => $this->localHint,
            'distance' => $this->distance,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'openingDays' => $this->openingDays,
            'holidayItems' => $this->holidayItems,
        ];
    }
}

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

final class PickupPointListQuery implements PickupPointListQueryInterface
{
    private string $carrier;

    private string $key;

    private string $zipCode;

    private string $requestId;

    private \DateTimeInterface $dateFrom;

    private ?string $address = null;

    private ?string $city = null;

    private ?string $countryCode = null;

    private ?int $maxPudoNumber = null;

    private ?int $maxDistanceSearch = null;

    private ?float $weight = null;

    private ?string $category = null;

    private ?bool $holidayTolerant = null;

    public function __construct()
    {
        $this->carrier = self::DEFAULT_CARRIER;
        $this->key = self::DEFAULT_KEY;
        $this->requestId = self::DEFAULT_REQUEST_ID;
        $this->dateFrom = new \DateTime();
    }

    public function getCarrier(): string
    {
        return $this->carrier;
    }

    public function setCarrier(string $carrier): void
    {
        $this->carrier = $carrier;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function setKey(string $key): void
    {
        $this->key = $key;
    }

    public function getZipCode(): string
    {
        return $this->zipCode;
    }

    public function setZipCode(string $zipCode): void
    {
        $this->zipCode = $zipCode;
    }

    public function getRequestId(): string
    {
        return $this->requestId;
    }

    public function setRequestId(string $requestId): void
    {
        $this->requestId = $requestId;
    }

    public function getDateFrom(): \DateTimeInterface
    {
        return $this->dateFrom;
    }

    public function setDateFrom(\DateTimeInterface $dateFrom): void
    {
        $this->dateFrom = $dateFrom;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): void
    {
        $this->address = $address;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): void
    {
        $this->city = $city;
    }

    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    public function setCountryCode(?string $countryCode): void
    {
        $this->countryCode = $countryCode;
    }

    public function getMaxPudoNumber(): ?int
    {
        return $this->maxPudoNumber;
    }

    public function setMaxPudoNumber(?int $maxPudoNumber): void
    {
        $this->maxPudoNumber = $maxPudoNumber;
    }

    public function getMaxDistanceSearch(): ?int
    {
        return $this->maxDistanceSearch;
    }

    public function setMaxDistanceSearch(?int $maxDistanceSearch): void
    {
        $this->maxDistanceSearch = $maxDistanceSearch;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight(?float $weight): void
    {
        $this->weight = $weight;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(?string $category): void
    {
        $this->category = $category;
    }

    public function getHolidayTolerant(): ?bool
    {
        return $this->holidayTolerant;
    }

    public function setHolidayTolerant(?bool $holidayTolerant): void
    {
        $this->holidayTolerant = $holidayTolerant;
    }
}

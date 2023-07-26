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

abstract class AbstractPickupPointListQuery
{
    public ?string $accountNumber;

    public ?string $password;

    public ?string $countryCode;

    public ?string $type;

    public ?string $productCode;

    public ?string $service;

    public ?string $weight;

    public ?string $shippingDate;

    public ?string $maxPointChronopost;

    public ?string $maxDistanceSearch;

    public ?string $holidayTolerant;

    public function __construct()
    {
        $this->type = PickupPointListQueryInterface::DEFAULT_TYPE;
        $this->service = PickupPointListQueryInterface::DEFAULT_SERVICE;
        $this->weight = PickupPointListQueryInterface::DEFAULT_WEIGHT;
        $this->shippingDate = (new \DateTime())->format('d/m/Y');
        $this->maxPointChronopost = PickupPointListQueryInterface::DEFAULT_MAX_POINT_CHRONOPOST;
        $this->maxDistanceSearch = PickupPointListQueryInterface::DEFAULT_MAX_DISTANCE_SEARCH;
        $this->holidayTolerant = PickupPointListQueryInterface::DEFAULT_HOLIDAY_TOLERANT;
    }

    public function getAccountNumber(): ?string
    {
        return $this->accountNumber;
    }

    public function setAccountNumber(?string $accountNumber): void
    {
        $this->accountNumber = $accountNumber;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    public function setCountryCode(?string $countryCode): void
    {
        $this->countryCode = $countryCode;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    public function getProductCode(): ?string
    {
        return $this->productCode;
    }

    public function setProductCode(?string $productCode): void
    {
        $this->productCode = $productCode;
    }

    public function getService(): ?string
    {
        return $this->service;
    }

    public function setService(?string $service): void
    {
        $this->service = $service;
    }

    public function getWeight(): ?string
    {
        return $this->weight;
    }

    public function setWeight(?string $weight): void
    {
        $this->weight = $weight;
    }

    public function getShippingDate(): ?string
    {
        return $this->shippingDate;
    }

    public function setShippingDate(?string $shippingDate): void
    {
        $this->shippingDate = $shippingDate;
    }

    public function getMaxPointChronopost(): ?string
    {
        return $this->maxPointChronopost;
    }

    public function setMaxPointChronopost(?string $maxPointChronopost): void
    {
        $this->maxPointChronopost = $maxPointChronopost;
    }

    public function getMaxDistanceSearch(): ?string
    {
        return $this->maxDistanceSearch;
    }

    public function setMaxDistanceSearch(?string $maxDistanceSearch): void
    {
        $this->maxDistanceSearch = $maxDistanceSearch;
    }

    public function getHolidayTolerant(): ?string
    {
        return $this->holidayTolerant;
    }

    public function setHolidayTolerant(?string $holidayTolerant): void
    {
        $this->holidayTolerant = $holidayTolerant;
    }
}

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

namespace MonsieurBiz\SyliusAdvancedShippingPlugin\Model\AddressAutocomplete;

class Location implements LocationInterface, \JsonSerializable
{
    private ?string $type = null;

    private ?string $identifier = null;

    private ?string $name = null;

    private ?string $address = null;

    private ?string $city = null;

    private ?string $postcode = null;

    private ?string $countryCode = null;

    private ?float $latitude = null;

    private ?float $longitude = null;

    public function __construct(?string $type, ?string $identifier, ?string $name, ?string $address, ?string $city, ?string $postcode, ?string $countryCode, ?float $latitude, ?float $longitude)
    {
        $this->type = $type;
        $this->identifier = $identifier;
        $this->name = $name;
        $this->address = $address;
        $this->city = $city;
        $this->postcode = $postcode;
        $this->countryCode = $countryCode;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function getPostcode(): ?string
    {
        return $this->postcode;
    }

    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function jsonSerialize(): array
    {
        return [
            'type' => $this->type,
            'identifier' => $this->identifier,
            'name' => $this->name,
            'address' => $this->address,
            'postcode' => $this->postcode,
            'city' => $this->city,
            'countryCode' => $this->countryCode,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
        ];
    }
}

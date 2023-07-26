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

namespace MonsieurBiz\SyliusAdvancedShippingPlugin\Api;

use MonsieurBiz\SyliusAdvancedShippingPlugin\Model\PickupPointInterface;

interface PickupPointClientInterface
{
    /**
     * @return PickupPointInterface[]
     */
    public function getPickupPointsByPostcodeAndCountry(string $postcode, string $countryCode): array;

    /**
     * @return PickupPointInterface[]
     */
    public function getPickupPointsByPostcode(string $postcode): array;

    public function getPickupPointsByLatitudeLongitude(string $countryCode, float $latitude, float $longitude): array;
}

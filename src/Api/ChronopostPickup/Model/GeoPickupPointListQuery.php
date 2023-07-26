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

final class GeoPickupPointListQuery extends AbstractPickupPointListQuery implements GeoPickupPointListQueryInterface
{
    public ?float $coordGeoLatitude;

    public ?float $coordGeoLongitude;

    public function getCoordGeoLatitude(): ?float
    {
        return $this->coordGeoLatitude;
    }

    public function setCoordGeoLatitude(?float $coordGeoLatitude): void
    {
        $this->coordGeoLatitude = $coordGeoLatitude;
    }

    public function getCoordGeoLongitude(): ?float
    {
        return $this->coordGeoLongitude;
    }

    public function setCoordGeoLongitude(?float $coordGeoLongitude): void
    {
        $this->coordGeoLongitude = $coordGeoLongitude;
    }
}

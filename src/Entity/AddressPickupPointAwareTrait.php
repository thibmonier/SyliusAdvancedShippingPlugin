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

namespace MonsieurBiz\SyliusAdvancedShippingPlugin\Entity;

use Doctrine\ORM\Mapping as ORM;

trait AddressPickupPointAwareTrait
{
    /**
     * @ORM\Column(name="pickup_point_type", type="string", length=255, nullable=true)
     */
    private ?string $pickupPointType = null;

    /**
     * @ORM\Column(name="pickup_point_code", type="string", length=255, nullable=true)
     */
    private ?string $pickupPointCode = null;

    public function getPickupPointType(): ?string
    {
        return $this->pickupPointType;
    }

    public function setPickupPointType(?string $pickupPointType): void
    {
        $this->pickupPointType = $pickupPointType;
    }

    public function getPickupPointCode(): ?string
    {
        return $this->pickupPointCode;
    }

    public function setPickupPointCode(?string $pickupPointCode): void
    {
        $this->pickupPointCode = $pickupPointCode;
    }
}

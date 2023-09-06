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

namespace App\Entity\Addressing;

use Doctrine\ORM\Mapping as ORM;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Entity\AddressPickupPointAwareTrait;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Entity\AddressTemporaryAwareTrait;
use Sylius\Component\Core\Model\Address as BaseAddress;

/**
 * @ORM\Entity
 *
 * @ORM\Table(name="sylius_address")
 */
#[ORM\Entity]
#[ORM\Table(name: 'sylius_address')]
class Address extends BaseAddress implements AddressInterface
{
    use AddressPickupPointAwareTrait;
    use AddressTemporaryAwareTrait;
}

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

use Doctrine\Common\Collections\Collection;
use Sylius\Component\Core\Model\AddressInterface;

interface TemporaryAddressesAwareInterface
{
    public function getTemporaryAddresses(): Collection;

    public function hasTemporaryAddress(AddressInterface $address): bool;

    public function addMethod(AddressInterface $address): void;

    public function removeMethod(AddressInterface $address): void;
}

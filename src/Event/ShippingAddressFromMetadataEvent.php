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

namespace MonsieurBiz\SyliusAdvancedShippingPlugin\Event;

use Sylius\Component\Core\Model\AddressInterface;

final class ShippingAddressFromMetadataEvent
{
    public function __construct(
        private ?AddressInterface $currentAddress,
        private AddressInterface $originalShippingAddress
    ) {
    }

    public function getCurrentAddress(): ?AddressInterface
    {
        return $this->currentAddress;
    }

    public function getOriginalAddress(): AddressInterface
    {
        return $this->originalShippingAddress;
    }
}

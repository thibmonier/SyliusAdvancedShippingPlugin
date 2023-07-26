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

namespace MonsieurBiz\SyliusAdvancedShippingPlugin\Checker;

use Sylius\Component\Addressing\Model\AddressInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\ShipmentInterface;

trait AddressBasedCheckerTrait
{
    public function getShippingAddress(ShipmentInterface $subject): ?AddressInterface
    {
        /** @var OrderInterface|null $order */
        $order = $subject->getOrder();
        if (null === $order || null === ($shippingAddress = $order->getShippingAddress())) {
            return null;
        }

        return null !== $shippingAddress->getPostcode() ? $shippingAddress : null;
    }
}

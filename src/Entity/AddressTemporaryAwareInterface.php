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

use Sylius\Component\Order\Model\OrderInterface;

interface AddressTemporaryAwareInterface
{
    public function setTemporary(bool $isTemporary): void;

    public function isTemporary(): bool;

    public function setSourceOrder(?OrderInterface $sourceOrder): void;

    public function getSourceOrder(): ?OrderInterface;
}

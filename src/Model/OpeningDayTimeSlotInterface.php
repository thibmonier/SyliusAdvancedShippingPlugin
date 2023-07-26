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

namespace MonsieurBiz\SyliusAdvancedShippingPlugin\Model;

interface OpeningDayTimeSlotInterface
{
    public function setStartTime(string $time): void;

    public function getStartTime(): ?string;

    public function setEndTime(string $time): void;

    public function getEndTime(): ?string;
}

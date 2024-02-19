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

class OpeningDayTimeSlot implements OpeningDayTimeSlotInterface, \JsonSerializable
{
    private ?string $startTime = null;

    private ?string $endTime = null;

    public function setStartTime(string $time): void
    {
        $this->startTime = $time;
    }

    public function getStartTime(): ?string
    {
        return $this->startTime;
    }

    public function setEndTime(string $time): void
    {
        $this->endTime = $time;
    }

    public function getEndTime(): ?string
    {
        return $this->endTime;
    }

    public function jsonSerialize(): array
    {
        return [
            'startTime' => $this->startTime,
            'endTime' => $this->endTime,
        ];
    }
}

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

class HolidayTimeSlot implements HolidayTimeSlotInterface, \JsonSerializable
{
    private ?\DateTime $startTime = null;

    private ?\DateTime $endTime = null;

    public function setStartTime(\DateTime $time): void
    {
        $this->startTime = $time;
    }

    public function getStartTime(): ?\DateTime
    {
        return $this->startTime;
    }

    public function setEndTime(\DateTime $time): void
    {
        $this->endTime = $time;
    }

    public function getEndTime(): ?\DateTime
    {
        return $this->endTime;
    }

    public function jsonSerialize(): array
    {
        return [
            'startTime' => null !== $this->startTime ? $this->startTime->format('Y-m-d') : null,
            'endTime' => null !== $this->endTime ? $this->endTime->format('Y-m-d') : null,
        ];
    }
}

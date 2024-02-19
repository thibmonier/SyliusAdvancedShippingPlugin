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

class OpeningDay implements OpeningDayInterface, \JsonSerializable
{
    private ?int $dayCode = null;

    private ?array $timeSlots = null;

    public function setDayCode(int $dayCode): void
    {
        $this->dayCode = $dayCode;
    }

    public function getDayCode(): ?int
    {
        return $this->dayCode;
    }

    public function setTimeSlots(array $timeSlots): void
    {
        $this->timeSlots = $timeSlots;
    }

    public function getTimeSlots(): ?array
    {
        return $this->timeSlots;
    }

    public function addTimeSlot(OpeningDayTimeSlotInterface $timeSlot): void
    {
        if (!\is_array($this->timeSlots)) {
            $this->timeSlots = [];
        }

        $this->timeSlots[] = $timeSlot;
    }

    public function jsonSerialize(): array
    {
        return [
            'dayCode' => $this->dayCode,
            'timeSlots' => $this->timeSlots,
        ];
    }
}

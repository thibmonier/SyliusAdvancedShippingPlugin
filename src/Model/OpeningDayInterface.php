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

interface OpeningDayInterface
{
    public function setDayCode(int $dayCode): void;

    public function getDayCode(): ?int;

    /**
     * @param OpeningDayTimeSlotInterface[] $timeSlots
     */
    public function setTimeSlots(array $timeSlots): void;

    /**
     * @return ?OpeningDayTimeSlotInterface[]
     */
    public function getTimeSlots(): ?array;

    public function addTimeSlot(OpeningDayTimeSlotInterface $timeSlot): void;
}

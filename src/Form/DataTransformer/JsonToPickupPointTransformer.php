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

namespace MonsieurBiz\SyliusAdvancedShippingPlugin\Form\DataTransformer;

use MonsieurBiz\SyliusAdvancedShippingPlugin\Model\HolidayTimeSlot;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Model\OpeningDay;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Model\OpeningDayTimeSlot;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Model\PickupPoint;
use Symfony\Component\Form\DataTransformerInterface;

final class JsonToPickupPointTransformer implements DataTransformerInterface
{
    /**
     * @param object|null $value
     */
    public function transform($value): ?string
    {
        $encoding = json_encode($value, \JSON_HEX_TAG | \JSON_HEX_APOS | \JSON_HEX_AMP | \JSON_HEX_QUOT);

        return $encoding ?: null;
    }

    /**
     * @param string|null $value
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function reverseTransform($value): ?object
    {
        if (null === $value) {
            return null;
        }

        $data = json_decode((string) $value, true);

        if (empty($data)) {
            return null;
        }

        $pickupPoint = new PickupPoint();
        $pickupPoint->setIdentifier($data['identifier'] ?? '');
        $pickupPoint->setName($data['name']);
        $pickupPoint->setAddress1($data['address1']);
        $pickupPoint->setAddress2($data['address2']);
        $pickupPoint->setAddress3($data['address3']);
        $pickupPoint->setCountryCode($data['countryCode'] ?? '');
        $pickupPoint->setPostcode($data['postcode']);
        $pickupPoint->setCity($data['city']);
        $pickupPoint->setLocalHint($data['localHint']);
        $pickupPoint->setDistance($data['distance']);
        $pickupPoint->setLatitude($data['latitude']);
        $pickupPoint->setLongitude($data['longitude']);

        $days = [];
        foreach ($data['openingDays'] as $dayData) {
            $day = new OpeningDay();
            $day->setDayCode($dayData['dayCode']);
            $slots = [];
            foreach ($dayData['timeSlots'] as $slotData) {
                $slot = new OpeningDayTimeSlot();
                $slot->setStartTime($slotData['startTime']);
                $slot->setEndTime($slotData['endTime']);
                $slots[] = $slot;
            }
            $day->setTimeSlots($slots);
            $days[] = $day;
        }
        $pickupPoint->setOpeningsDays($days);

        $items = [];

        if ($data['holidayItems']) {
            foreach ($data['holidayItems'] as $itemData) {
                $startTime = \DateTime::createFromFormat('d/m/Y', $itemData['startTime']);
                $endTime = \DateTime::createFromFormat('d/m/Y', $itemData['startTime']);
                if (false === $startTime || false === $endTime) {
                    continue;
                }

                $item = new HolidayTimeSlot();
                $item->setStartTime($startTime);
                $item->setEndTime($endTime);
            }
            $pickupPoint->setHolidayItems($items);
        }

        return $pickupPoint;
    }
}

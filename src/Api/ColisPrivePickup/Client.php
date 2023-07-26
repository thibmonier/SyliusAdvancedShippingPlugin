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

namespace MonsieurBiz\SyliusAdvancedShippingPlugin\Api\ColisPrivePickup;

use MonsieurBiz\SyliusAdvancedShippingPlugin\Api\ColisPrivePickup\Config\ColisPrivePickupConfigInterface;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Api\ColisPrivePickup\Model\PickupPointListQuery;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Api\ColisPrivePickup\Model\PickupPointListQueryInterface;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Exception\MissingApiConfigurationParamException;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Model\OpeningDay;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Model\OpeningDayTimeSlot;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Model\PickupPoint;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Model\PickupPointInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class Client implements ClientInterface
{
    use LoggerAwareTrait;

    private const FACTOR_DISTANCE_METER = 1000;

    public function __construct(private ColisPrivePickupConfigInterface $config, private HttpClientInterface $httpClient)
    {
        $this->logger = new NullLogger();
    }

    public static function create(ColisPrivePickupConfigInterface $config): ClientInterface
    {
        self::validateConfig($config);
        $httpClient = HttpClient::create();

        return new static($config, $httpClient);
    }

    public static function validateConfig(ColisPrivePickupConfigInterface $config): void
    {
        $params = [
            'url' => 'getApiUrl',
            'account ID' => 'getAccountId',
            'country code' => 'getCountryCode',
            'result limit' => 'getResultLimit',
        ];

        foreach ($params as $param => $getter) {
            if (empty($config->{$getter}())) {
                throw new MissingApiConfigurationParamException(sprintf('The param %s is mandatory for Colis Privé client', $param));
            }
        }
    }

    public function getPickupPoints(PickupPointListQueryInterface $query): array
    {
        try {
            $params = [
                'accountid' => $this->config->getAccountId(),
                'country' => $query->getCountryCode() ?? $this->config->getCountryCode(),
                'results' => $this->config->getResultLimit() ?? '',
                'preparationdelay' => $this->config->getDefaultDelay() ?? '',
                'extratag' => 'code_dest',
                'format' => 'JSON',
                'zip' => $query->getZipCode(),
                'street' => $query->getStreet() ?? '',
                'city' => $query->getCity() ?? '',
                'lat' => $query->getLatitude() ?? '',
                'lon' => $query->getLongitude() ?? '',
            ];

            $url = sprintf('%s?%s', $this->config->getApiUrl(), http_build_query($params));
            $response = $this->httpClient->request('GET', $url);

            return $this->transformResponseContent($response->toArray());
        } catch (\Exception $e) {
            $this->logger?->error((string) $e);

            return [];
        }
    }

    private function transformResponseContent(array $content): array
    {
        $list = [];

        foreach ($content['accessPointList'] ?? [] as $item) {
            $pickupPoint = $this->createPickupPoint($item);
            if (null === $pickupPoint) {
                continue;
            }
            $list[$pickupPoint->getIdentifier()] = $pickupPoint;
        }

        return $list;
    }

    private function createPickupPoint(array $pickupPointData): ?PickupPointInterface
    {
        $pickupPoint = new PickupPoint();

        try {
            $pickupPoint->setIdentifier($pickupPointData['id']);
            $pickupPoint->setName($pickupPointData['name']);
            $pickupPoint->setAddress1($pickupPointData['address']['street']);
            $pickupPoint->setAddress2('');
            $pickupPoint->setAddress3('');
            $pickupPoint->setLocalHint('');
            $pickupPoint->setDistance($this->convertDistance($pickupPointData['distance']));
            $pickupPoint->setPostcode($pickupPointData['address']['zip']);
            $pickupPoint->setCity($pickupPointData['address']['city']);
            $pickupPoint->setLatitude($pickupPointData['coordinate']['latitude']);
            $pickupPoint->setLongitude($pickupPointData['coordinate']['longitude']);
            $pickupPoint->setCountryCode($pickupPointData['address']['country']);

            $openingDays = $this->createOpeningDays($pickupPointData['openingHours']);
            $pickupPoint->setOpeningsDays($openingDays);
        } catch (\Exception $e) {
            $this->logger?->error((string) $e->getMessage());

            return null;
        }

        return $pickupPoint;
    }

    private function convertDistance(float $distance): int
    {
        return (int) ($distance * self::FACTOR_DISTANCE_METER);
    }

    private function createOpeningDays(array $openingHoursData): array
    {
        $openingDays = [];

        foreach ($openingHoursData as $openingHourData) {
            $dayCode = (int) (new \DateTime())->setTimestamp(strtotime($openingHourData['name']))->format('w');
            $openingDay = $openingDays[$dayCode] ?? new OpeningDay();
            $openingDay->setDayCode($dayCode);
            $slots = $openingDay->getTimeSlots() ?? [];

            foreach ($openingHourData['timespanList'] as $timespan) {
                $slot = new OpeningDayTimeSlot();
                $slot->setStartTime($timespan['start']);
                $slot->setEndTime($timespan['end']);
                $slots[] = $slot;
            }

            $openingDay->setTimeSlots($slots);
            $openingDays[$dayCode] = $openingDay;
        }

        return $openingDays;
    }

    /**
     * @return PickupPointInterface[]
     */
    public function getPickupPointsByPostcodeAndCountry(string $postcode, string $countryCode): array
    {
        $query = new PickupPointListQuery();
        $query->setZipCode($postcode);
        $query->setCountryCode($countryCode);

        return $this->getPickupPoints($query);
    }

    public function getPickupPointsByPostcode(string $postcode): array
    {
        $query = new PickupPointListQuery();
        $query->setZipCode($postcode);

        return $this->getPickupPoints($query);
    }

    public function getPickupPointsByLatitudeLongitude(string $countryCode, float $latitude, float $longitude): array
    {
        $query = new PickupPointListQuery();
        $query->setCountryCode($countryCode);
        $query->setLatitude($latitude);
        $query->setLongitude($longitude);

        return $this->getPickupPoints($query);
    }
}

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

namespace MonsieurBiz\SyliusAdvancedShippingPlugin\Api\DpdPickup;

use MonsieurBiz\SyliusAdvancedShippingPlugin\Api\DpdPickup\Config\DpdPickupConfigInterface;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Api\DpdPickup\Model\PickupPointListQuery;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Api\DpdPickup\Model\PickupPointListQueryInterface;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Exception\MissingApiConfigurationParamException;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Model\HolidayTimeSlot;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Model\OpeningDay;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Model\OpeningDayTimeSlot;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Model\PickupPoint;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Model\PickupPointInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class Client implements ClientInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    private DpdPickupConfigInterface $config;

    private HttpClientInterface $httpClient;

    public function __construct(DpdPickupConfigInterface $config, HttpClientInterface $httpClient)
    {
        $this->config = $config;
        $this->httpClient = $httpClient;
        $this->logger = new NullLogger();
    }

    public static function create(DpdPickupConfigInterface $config): ClientInterface
    {
        self::validateConfig($config);
        $httpClient = HttpClient::create();

        return new static($config, $httpClient);
    }

    public static function validateConfig(DpdPickupConfigInterface $config): void
    {
        $params = [
            'url' => 'getPickupApiUrl',
            'key' => 'getPickupApiKey',
        ];

        foreach ($params as $param => $getter) {
            if (empty($config->{$getter}())) {
                throw new MissingApiConfigurationParamException(sprintf('The param %s is mandatary for DPD Pickup client', $param));
            }
        }
    }

    /**
     * @return PickupPointInterface[]
     */
    public function getPickupPoints(PickupPointListQueryInterface $query): array
    {
        try {
            $params = [
                'carrier' => $query->getCarrier(),
                'key' => $query->getKey(),
                'address' => $query->getAddress() ?? '',
                'zipCode' => $query->getZipCode(),
                'city' => $query->getCity() ?? '',
                'countrycode' => $query->getCountryCode() ?? '',
                'requestID' => $query->getRequestId(),
                'date_from' => $query->getDateFrom()->format('d/m/Y'),
                'max_pudo_number' => $query->getMaxPudoNumber() ?? '',
                'max_distance_search' => $query->getMaxDistanceSearch() ?? '',
                'weight' => $query->getWeight() ?? '',
                'category' => $query->getCategory() ?? '',
                'holiday_tolerant' => $query->getHolidayTolerant() ?? '',
            ];

            $url = sprintf('%s?%s', $this->config->getPickupApiUrl(), http_build_query($params));
            $response = $this->httpClient->request('GET', $url);

            return $this->transformResponseContent($response->getContent());
        } catch (\Exception $e) {
            $this->logger?->error((string) $e);

            return [];
        }
    }

    private function transformResponseContent(string $content): array
    {
        $list = [];
        $document = new \DOMDocument();
        $document->loadXML($content);
        $items = $document->getElementsByTagName('PUDO_ITEM');
        foreach ($items as $item) {
            $pickupPoint = $this->createPickupPoint($item);
            if (null === $pickupPoint) {
                continue;
            }
            $list[$pickupPoint->getIdentifier()] = $pickupPoint;
        }

        return $list;
    }

    private function createPickupPoint(\DOMElement $node): ?PickupPointInterface
    {
        $item = simplexml_import_dom($node);
        if (null === $item) {
            return null;
        }

        $pickupPoint = new PickupPoint();
        $pickupPoint->setIdentifier($this->getCleanedString($item, 'PUDO_ID'));
        $pickupPoint->setName($this->getCleanedString($item, 'NAME'));
        $pickupPoint->setAddress1($this->getCleanedString($item, 'ADDRESS1'));
        $pickupPoint->setAddress2($this->getCleanedString($item, 'ADDRESS2'));
        $pickupPoint->setAddress3($this->getCleanedString($item, 'ADDRESS3'));
        $pickupPoint->setLocalHint($this->getCleanedString($item, 'LOCAL_HINT'));
        $pickupPoint->setDistance($this->getCleanedInt($item, 'DISTANCE'));
        $pickupPoint->setPostcode($this->getCleanedString($item, 'ZIPCODE'));
        $pickupPoint->setCity($this->getCleanedString($item, 'CITY'));
        $pickupPoint->setLatitude($this->getCleanedFloat($item, 'LATITUDE'));
        $pickupPoint->setLongitude($this->getCleanedFloat($item, 'LONGITUDE'));
        $pickupPoint->setCountryCode('FR');

        $openingDays = $this->createOpeningDays($node);
        $pickupPoint->setOpeningsDays($openingDays);
        $holidayItems = $this->createHolidayItems($node);
        $pickupPoint->setHolidayItems($holidayItems);

        return $pickupPoint;
    }

    /**
     * @return PickupPointInterface[]
     */
    public function getPickupPointsByPostcodeAndCountry(string $postcode, string $countryCode): array
    {
        $query = new PickupPointListQuery();
        $query->setZipCode($postcode);
        // @todo: implement country for DPD Pickup

        return $this->getPickupPoints($query);
    }

    /**
     * @return PickupPointInterface[]
     */
    public function getPickupPointsByPostcode(string $postcode): array
    {
        $query = new PickupPointListQuery();
        $query->setZipCode($postcode);

        return $this->getPickupPoints($query);
    }

    private function getCleanedString(\SimpleXMLElement $element, string $parameter): string
    {
        return trim((string) $element->{$parameter});
    }

    private function getCleanedInt(\SimpleXMLElement $element, string $parameter): int
    {
        return (int) $this->getCleanedString($element, $parameter);
    }

    private function getCleanedFloat(\SimpleXMLElement $element, string $parameter): float
    {
        return (float) str_replace(',', '.', $this->getCleanedString($element, $parameter));
    }

    private function createOpeningDays(\DOMElement $node): array
    {
        $openingDays = [];
        foreach ($node->getElementsByTagName('OPENING_HOURS_ITEM') as $data) {
            $data = simplexml_import_dom($data);
            if (null === $data) {
                continue;
            }
            $dayCode = (int) $data->DAY_ID;
            $openingDay = $openingDays[$dayCode] ?? new OpeningDay();
            $openingDay->setDayCode($dayCode);
            $slots = $openingDay->getTimeSlots() ?? [];
            $slot = new OpeningDayTimeSlot();
            $slot->setStartTime($this->getCleanedString($data, 'START_TM'));
            $slot->setEndTime($this->getCleanedString($data, 'END_TM'));
            $slots[] = $slot;
            $openingDay->setTimeSlots($slots);
            $openingDays[$dayCode] = $openingDay;
        }

        return $openingDays;
    }

    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    private function createHolidayItems(\DOMElement $node): array
    {
        $holidayItems = [];
        foreach ($node->getElementsByTagName('HOLIDAY_ITEM') as $data) {
            $data = simplexml_import_dom($data);
            if (null === $data) {
                continue;
            }
            $startTime = \DateTime::createFromFormat('d/m/Y', $this->getCleanedString($data, 'START_DTM'));
            $endTime = \DateTime::createFromFormat('d/m/Y', $this->getCleanedString($data, 'END_DTM'));
            if (false === $startTime || false === $endTime) {
                continue;
            }
            $holidayTimeSlot = new HolidayTimeSlot();
            $holidayTimeSlot->setStartTime($startTime);
            $holidayTimeSlot->setEndTime($endTime);
            $holidayItems[] = $holidayTimeSlot;
        }

        return $holidayItems;
    }

    public function getPickupPointsByLatitudeLongitude(string $countryCode, float $latitude, float $longitude): array
    {
        // @todo: implement for DPD Pickup
        return [];
    }
}

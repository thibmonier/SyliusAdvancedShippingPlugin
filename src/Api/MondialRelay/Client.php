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

namespace MonsieurBiz\SyliusAdvancedShippingPlugin\Api\MondialRelay;

use MondialRelay\ApiClient;
use MondialRelay\BussinessHours\BussinessHours;
use MondialRelay\Point\Point;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Api\MondialRelay\Config\MondialRelayConfigInterface;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Exception\MissingApiConfigurationParamException;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Model\OpeningDay;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Model\OpeningDayTimeSlot;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Model\OpeningDayTimeSlotInterface;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Model\PickupPoint;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Model\PickupPointInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;

final class Client implements ClientInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    public function __construct(private MondialRelayConfigInterface $config, private ApiClient $apiClient)
    {
        $this->logger = new NullLogger();
    }

    public static function create(MondialRelayConfigInterface $config): ClientInterface
    {
        self::validateConfig($config);
        $wsdl = rtrim($config->getUrl(), ' /') . '?WSDL';
        $soapClient = new \SoapClient($wsdl);
        $apiClient = new ApiClient($soapClient, $config->getIdentifier(), $config->getKey());

        return new static($config, $apiClient);
    }

    public static function validateConfig(MondialRelayConfigInterface $config): void
    {
        $params = [
            'url' => 'getUrl',
            'identifier' => 'getIdentifier',
            'key' => 'getKey',
            'country' => 'getCountry',
            'result limit' => 'getResultLimit',
        ];

        foreach ($params as $param => $getter) {
            if (empty($config->{$getter}())) {
                throw new MissingApiConfigurationParamException(sprintf('The param %s is mandatary for Mondial Relay client', $param));
            }
        }
    }

    private function createPickupPoint(Point $deliveryPoint): PickupPointInterface
    {
        $pickupPoint = new PickupPoint();
        $pickupPoint->setIdentifier($deliveryPoint->id());
        $pickupPoint->setName(trim($deliveryPoint->address()[0] ?? '') . (!empty($deliveryPoint->address()[1]) ? ' ' . trim($deliveryPoint->address()[1]) : ''));
        $pickupPoint->setAddress1(trim($deliveryPoint->address()[2] ?? '')); // The main address information is on #2 of the address array
        $pickupPoint->setAddress2(trim($deliveryPoint->address()[3] ?? ''));
        $pickupPoint->setAddress3('');
        $pickupPoint->setLocalHint(implode('', $deliveryPoint->location()));
        $pickupPoint->setDistance((int) $deliveryPoint->distance());
        $pickupPoint->setPostcode(trim($deliveryPoint->cp()));
        $pickupPoint->setCity(trim($deliveryPoint->city()));
        $pickupPoint->setLatitude((float) $deliveryPoint->latitude());
        $pickupPoint->setLongitude((float) $deliveryPoint->longitude());
        $pickupPoint->setCountryCode(trim($deliveryPoint->country()));

        $openingDays = $this->createOpeningDays($deliveryPoint);
        $pickupPoint->setOpeningsDays($openingDays);

        return $pickupPoint;
    }

    public function getPickupPointsByLatitudeLongitude(string $countryCode, float $latitude, float $longitude): array
    {
        return $this->findDeliveryPoints($this->buildParams([
            'Pays' => $countryCode,
            'Latitude' => $latitude,
            'Longitude' => $longitude,
        ]));
    }

    /**
     * @return PickupPointInterface[]
     */
    public function getPickupPointsByPostcodeAndCountry(string $postcode, string $countryCode): array
    {
        return $this->findDeliveryPoints(
            $this->buildParams(['CP' => $postcode, 'Pays' => $countryCode])
        );
    }

    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function getPickupPointsByPostcode(string $postcode): array
    {
        return $this->findDeliveryPoints(
            $this->buildParams(['CP' => $postcode])
        );
    }

    private function findDeliveryPoints(array $params): array
    {
        $pickupPoints = [];

        try {
            $deliveryPoints = $this->apiClient->findDeliveryPoints($params);
            foreach ($deliveryPoints as $deliveryPoint) {
                $pickupPoints[] = $this->createPickupPoint($deliveryPoint);
            }
        } catch (\Exception $e) {
            $this->logger?->critical($e->getMessage());
        } finally {
            return $pickupPoints;
        }
    }

    private function buildParams(array $params): array
    {
        return array_merge([
            'Pays' => $this->config->getCountry(),
            'Ville' => '',
            'CP' => '',
            'Latitude' => '',
            'Longitude' => '',
            'Taille' => '',
            'Poids' => '',
            'Action' => $this->config->getAction() ?: '',
            'DelaiEnvoi' => (string) ($this->config->getDefaultDelay() ?: MondialRelayConfigInterface::DEFAULT_DELAY),
            'RayonRecherche' => (string) ($this->config->getSearchArea() ?: MondialRelayConfigInterface::DEFAULT_SEARCH_AREA),
            'NombreResultats' => (string) $this->config->getResultLimit(),
        ], $params);
    }

    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    private function createOpeningDays(Point $deliveryPoint): array
    {
        $openingDays = [];
        /** @var BussinessHours $businessHour */
        foreach ($deliveryPoint->business_hours() as $businessHour) {
            if ('0000' === $businessHour->openingTime1() && '0000' === $businessHour->openingTime2()) {
                continue;
            }

            $dayCode = (int) (new \DateTime())->setTimestamp(strtotime($businessHour->day()))->format('w');
            $openingDay = new OpeningDay();
            $openingDay->setDayCode($dayCode);

            if ('0000' !== $businessHour->openingTime1() && '0000' !== $businessHour->closingTime1()) {
                $openingDay->addTimeSlot($this->createTimeSlot(
                    $businessHour->openingTime1(),
                    $businessHour->closingTime1()
                ));
            }

            if ('0000' !== $businessHour->openingTime2() && '0000' !== $businessHour->closingTime2()) {
                $openingDay->addTimeSlot($this->createTimeSlot(
                    $businessHour->openingTime2(),
                    $businessHour->closingTime2()
                ));
            }

            $openingDays[$dayCode] = $openingDay;
        }

        return $openingDays;
    }

    private function createTimeSlot(string $opening, string $closing): OpeningDayTimeSlotInterface
    {
        $timeSlot = new OpeningDayTimeSlot();
        $timeSlot->setStartTime(implode(':', str_split($opening, 2)));
        $timeSlot->setEndTime(implode(':', str_split($closing, 2)));

        return $timeSlot;
    }
}

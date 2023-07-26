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

namespace MonsieurBiz\SyliusAdvancedShippingPlugin\Api\ChronopostPickup;

use MonsieurBiz\SyliusAdvancedShippingPlugin\Api\ChronopostPickup\Config\ChronopostPickupConfigInterface;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Api\ChronopostPickup\Model\AddressPickupPointListQuery;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Api\ChronopostPickup\Model\GeoPickupPointListQuery;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Api\ChronopostPickup\Model\PickupPointListQueryInterface;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Exception\MissingApiConfigurationParamException;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Model\HolidayTimeSlot;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Model\OpeningDay;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Model\OpeningDayInterface;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Model\OpeningDayTimeSlot;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Model\PickupPoint;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Model\PickupPointInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;

final class Client implements ClientInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    private const CLASS_MAP = [
        'recherchePointChronopost' => AddressPickupPointListQuery::class,
        'recherchePointChronopostParCoordonneesGeographiques' => GeoPickupPointListQuery::class,
    ];

    private ChronopostPickupConfigInterface $config;

    private \SoapClient $soapClient;

    public function __construct(ChronopostPickupConfigInterface $config, \SoapClient $soapClient)
    {
        $this->config = $config;
        $this->soapClient = $soapClient;
        $this->logger = new NullLogger();
    }

    public static function create(ChronopostPickupConfigInterface $config): ClientInterface
    {
        self::validateConfig($config);

        $soapClient = new \SoapClient(
            $config->getPickupApiUrl(),
            ['classmap' => self::CLASS_MAP, 'trace' => 1]
        );

        return new static($config, $soapClient);
    }

    public static function validateConfig(ChronopostPickupConfigInterface $config): void
    {
        $params = [
            'url' => 'getPickupApiUrl',
            'account_number' => 'getPickupApiAccountNumber',
            'password' => 'getPickupApiPassword',
        ];

        foreach ($params as $param => $getter) {
            if (empty($config->{$getter}())) {
                throw new MissingApiConfigurationParamException(sprintf('The param %s is mandatary Chronopost DPD Pickup client', $param));
            }
        }
    }

    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function getPickupPoints(string $service, PickupPointListQueryInterface $query): array
    {
        $list = [];

        try {
            $response = $this->soapClient->__soapCall($service, [$query]);
            if (false === $this->isValidResponse($response)) {
                return $list;
            }

            foreach ($response->return->listePointRelais as $data) {
                if (false === $data->actif) {
                    continue;
                }

                $pickupPoint = new PickupPoint();
                $pickupPoint->setIdentifier($data->identifiant);
                $pickupPoint->setName($data->nom);
                $pickupPoint->setAddress1($data->adresse1);
                $pickupPoint->setAddress2($data->adresse2);
                $pickupPoint->setAddress3($data->adresse3);
                $pickupPoint->setPostcode($data->codePostal);
                $pickupPoint->setCity($data->localite);
                $pickupPoint->setLocalHint($data->indiceDeLocalisation);
                $pickupPoint->setDistance((int) $data->distanceEnMetre);
                $pickupPoint->setLatitude((float) $data->coordGeolocalisationLatitude);
                $pickupPoint->setLongitude((float) $data->coordGeolocalisationLongitude);
                $pickupPoint->setCountryCode($data->codePays);

                $openingDays = $this->createOpeningDays($data);
                $pickupPoint->setOpeningsDays($openingDays);

                $holidayTimeSlots = $this->createHolidayTimeSlots($data);
                $pickupPoint->setHolidayItems($holidayTimeSlots);

                $list[] = $pickupPoint;
            }
        } catch (\Exception $e) {
            $this->logger?->error((string) $e);

            return $list;
        }

        return $list;
    }

    private function createHolidayTimeSlots(\stdClass $pickupPoint): array
    {
        if ($this->isValidHolidayDateList($pickupPoint)) {
            return [];
        }

        $holidayTimeSlots = [];
        foreach ($pickupPoint->listePeriodeFermeture ?? [] as $data) {
            if ($this->isValidHolidaySlot($data)) {
                continue;
            }

            $holidayTimeSlot = new HolidayTimeSlot();
            $holidayTimeSlot->setStartTime(new \DateTime($data->calendarDeDebut));
            $holidayTimeSlot->setEndTime(new \DateTime($data->calendarDeFin));
            $holidayTimeSlots[] = $holidayTimeSlot;
        }

        return $holidayTimeSlots;
    }

    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    private function createOpeningDays(\stdClass $pickupPoint): array
    {
        $openingDays = [];
        foreach ($pickupPoint->listeHoraireOuverture ?? [] as $data) {
            if (false === isset($data->listeHoraireOuverture) || false === isset($data->jour)) {
                continue;
            }

            $openingDay = new OpeningDay();
            $openingDay->setDayCode($data->jour);
            $timeSlots = [];

            // API does not return an array when only one result, so whe manage it
            if (isset($data->listeHoraireOuverture->debut, $data->listeHoraireOuverture->fin)) {
                $dataSlot = new \stdClass();
                $dataSlot->debut = $data->listeHoraireOuverture->debut;
                $dataSlot->fin = $data->listeHoraireOuverture->fin;
                $data->listeHoraireOuverture = [$dataSlot];
            }

            foreach ($data->listeHoraireOuverture as $dataSlot) {
                $slot = new OpeningDayTimeSlot();
                $slot->setStartTime($dataSlot->debut);
                $slot->setEndTime($dataSlot->fin);
                $timeSlots[] = $slot;
            }

            $openingDay->setTimeSlots($timeSlots);
            $openingDays[] = $openingDay;
        }
        usort($openingDays, fn (OpeningDayInterface $first, OpeningDayInterface $second) => $first->getDayCode() <=> $second->getDayCode());

        return $openingDays;
    }

    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    private function isValidResponse(\stdClass $response): bool
    {
        return
            isset($response->return)
            && isset($response->return->errorCode)
            && 0 === $response->return->errorCode
            && isset($response->return->listePointRelais)
            && 0 < \count($response->return->listePointRelais);
    }

    /**
     * @return PickupPointInterface[]
     */
    public function getPickupPointsByPostcode(string $postcode): array
    {
        return $this->getPickupPointsByPostcodeAndCountry($postcode, 'FR');
    }

    /**
     * @return PickupPointInterface[]
     */
    public function getPickupPointsByPostcodeAndCountry(string $postcode, string $countryCode): array
    {
        $query = new AddressPickupPointListQuery();
        $query->setAccountNumber($this->config->getPickupApiAccountNumber());
        $query->setPassword($this->config->getPickupApiPassword());
        $query->setZipCode($postcode);
        $query->setCountryCode($countryCode);

        return $this->getPickupPoints('recherchePointChronopost', $query);
    }

    public function getPickupPointsByLatitudeLongitude(string $countryCode, float $latitude, float $longitude): array
    {
        $query = new GeoPickupPointListQuery();
        $query->setAccountNumber($this->config->getPickupApiAccountNumber());
        $query->setPassword($this->config->getPickupApiPassword());
        $query->setCoordGeoLatitude($latitude);
        $query->setCoordGeoLongitude($longitude);
        $query->setCountryCode($countryCode);

        return $this->getPickupPoints('recherchePointChronopostParCoordonneesGeographiques', $query);
    }

    private function isValidHolidayDateList(\stdClass $pickupPoint): bool
    {
        return
            false === isset($pickupPoint->listePeriodeFermeture)
            || false === \is_array($pickupPoint->listePeriodeFermeture);
    }

    private function isValidHolidaySlot(\stdClass $data): bool
    {
        return
            false === isset($data->calendarDeDebut)
            || false === isset($data->calendarDeFin);
    }
}

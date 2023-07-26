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

namespace MonsieurBiz\SyliusAdvancedShippingPlugin\Api\OpenStreetMap;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Model\AddressAutocomplete\Location;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class Client implements ClientInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    public function __construct(private HttpClientInterface $httpClient)
    {
        $this->logger = new NullLogger();
    }

    public static function create(): ClientInterface
    {
        $httpClient = HttpClient::create();

        return new static($httpClient);
    }

    public function search(string $query, string $country, int $limit): Collection
    {
        $params = [
            'q' => $query,
            'countrycodes' => $country,
            'limit' => $limit,
            'format' => 'json',
            'addressdetails' => 1,
        ];
        $locations = [];

        try {
            $url = sprintf('%s?%s', self::NOMINATIM_API_URL . '/search', http_build_query($params));
            $response = $this->httpClient->request('GET', $url);
            foreach ($response->toArray() as $place) {
                $address = $place['address'] ?? [];
                $locations[] = new Location(
                    (string) ($place['type'] ?? null),
                    (string) ($place['place_id'] ?? null),
                    (string) ($place['display_name'] ?? null),
                    (string) ($address['road'] ?? null),
                    (string) ($address['city'] ?? null),
                    (string) ($address['postcode'] ?? null),
                    (string) ($address['country_code'] ?? null),
                    (float) ($place['lat'] ?? null),
                    (float) ($place['lon'] ?? null),
                );
            }
        } catch (\Exception $e) {
            $this->logger?->critical($e->getMessage());
        }

        // @phpstan-ignore-next-line
        return new ArrayCollection($locations);
    }
}

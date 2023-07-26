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

namespace MonsieurBiz\SyliusAdvancedShippingPlugin\Provider\ShippingAddressProvider;

use MonsieurBiz\SyliusAdvancedShippingPlugin\Api\PickupPointClientInterface;

abstract class AbstractPickupShippingAddressProvider extends AbstractShippingAddressProvider
{
    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function getAddressListFromMetadata(array $metadata): array
    {
        $client = $this->getClient();

        if (false === empty($metadata['postcode']) && false === empty($metadata['countryCode'])) {
            return $client->getPickupPointsByPostcodeAndCountry($metadata['postcode'], $metadata['countryCode']);
        }

        if (false === empty($metadata['postcode'])) {
            return $client->getPickupPointsByPostcode($metadata['postcode']);
        }

        if (false === empty($metadata['latitude']) && false === empty($metadata['longitude']) && false === empty($metadata['countryCode'])) {
            return $client->getPickupPointsByLatitudeLongitude(
                $metadata['countryCode'],
                (float) $metadata['latitude'],
                (float) $metadata['longitude']
            );
        }

        return [];
    }

    abstract protected function getClient(): PickupPointClientInterface;
}

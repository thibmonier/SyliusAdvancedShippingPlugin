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

namespace MonsieurBiz\SyliusAdvancedShippingPlugin\Provider\AddressAutocompleteProvider;

use Doctrine\Common\Collections\Collection;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Api\OpenStreetMap\ClientFactoryInterface;

class OpenStreetMapAutocompleteProvider implements AddressAutocompleteProviderInterface
{
    public const TYPE = 'open_street_map';

    public function __construct(private ClientFactoryInterface $clientFactory)
    {
    }

    public function search(string $query, string $country, int $limit): Collection
    {
        $client = $this->clientFactory->create();

        return $client->search($query, $country, $limit);
    }
}

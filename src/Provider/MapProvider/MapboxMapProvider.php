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

namespace MonsieurBiz\SyliusAdvancedShippingPlugin\Provider\MapProvider;

final class MapboxMapProvider extends AbstractMapProvider implements MapProviderInterface
{
    public const TYPE = 'mapbox';

    public function getType(): string
    {
        return self::TYPE;
    }

    public function getUrl(): string
    {
        return 'https://api.mapbox.com/styles/v1/mapbox/streets-v11/tiles/{z}/{x}/{y}?access_token=' . $this->getConfiguration()['accessToken'] ?? '';
    }

    public function getParameters(): array
    {
        return [
            'attribution' => $this->getConfiguration()['attribution'] ?? '',
        ];
    }
}

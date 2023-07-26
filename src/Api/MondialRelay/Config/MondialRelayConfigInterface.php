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

namespace MonsieurBiz\SyliusAdvancedShippingPlugin\Api\MondialRelay\Config;

interface MondialRelayConfigInterface
{
    public const ACTION_SMA = 'SMA';

    public const ACTION_APM = 'APM';

    public const ACTION_REL = 'REL';

    public const ACTION_MED = 'MED';

    public const ACTION_24R = '24R';

    public const ACTION_24L = '24L';

    public const ACTION_XOH = 'XOH';

    public const ACTIONS = [
        self::ACTION_SMA,
        self::ACTION_APM,
        self::ACTION_REL,
        self::ACTION_MED,
        self::ACTION_24R,
        self::ACTION_24L,
        self::ACTION_XOH,
    ];

    public const DEFAULT_COUNTRY = 'FR';

    public const DEFAULT_RESULT_LIMIT = 10;

    public const DEFAULT_DELAY = 0;

    public const DEFAULT_SEARCH_AREA = 20;

    public function getUrl(): string;

    public function setUrl(string $url): void;

    public function getIdentifier(): string;

    public function setIdentifier(string $identifier): void;

    public function getKey(): string;

    public function setKey(string $key): void;

    public function getCountry(): string;

    public function setCountry(string $country): void;

    public function getResultLimit(): int;

    public function setResultLimit(int $resultLimit): void;

    public function getAction(): ?string;

    public function setAction(?string $action): void;

    public function getDefaultDelay(): ?int;

    public function setDefaultDelay(?int $defaultDelay): void;

    public function getSearchArea(): ?int;

    public function setSearchArea(?int $searchArea): void;
}

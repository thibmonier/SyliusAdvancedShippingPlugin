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

/*
 * This file is part of terravita corporate website.
 *
 * (c) terravita <sylius+terravita@monsieurbiz.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MonsieurBiz\SyliusAdvancedShippingPlugin\Api\ColisPrivePickup\Config;

interface ColisPrivePickupConfigInterface
{
    public function getApiUrl(): string;

    public function setApiUrl(string $apiUrl): void;

    public function getAccountId(): string;

    public function setAccountId(string $accountId): void;

    public function getCountryCode(): string;

    public function setCountryCode(string $countryCode): void;

    public function getDefaultDelay(): ?int;

    public function setDefaultDelay(int $defaultDelay): void;

    public function getResultLimit(): ?int;

    public function setResultLimit(int $resultLimit): void;
}

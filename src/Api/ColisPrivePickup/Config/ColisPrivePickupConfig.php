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

namespace MonsieurBiz\SyliusAdvancedShippingPlugin\Api\ColisPrivePickup\Config;

class ColisPrivePickupConfig implements ColisPrivePickupConfigInterface
{
    private string $apiUrl;

    private string $accountId;

    private string $countryCode;

    private int $defaultDelay;

    private ?int $resultLimit = null;

    public function getApiUrl(): string
    {
        return $this->apiUrl;
    }

    public function setApiUrl(string $apiUrl): void
    {
        $this->apiUrl = $apiUrl;
    }

    public function getAccountId(): string
    {
        return $this->accountId;
    }

    public function setAccountId(string $accountId): void
    {
        $this->accountId = $accountId;
    }

    public function getCountryCode(): string
    {
        return $this->countryCode;
    }

    public function setCountryCode(string $countryCode): void
    {
        $this->countryCode = $countryCode;
    }

    public function getDefaultDelay(): ?int
    {
        return $this->defaultDelay;
    }

    public function setDefaultDelay(int $defaultDelay): void
    {
        $this->defaultDelay = $defaultDelay;
    }

    public function getResultLimit(): ?int
    {
        return $this->resultLimit;
    }

    public function setResultLimit(int $resultLimit): void
    {
        $this->resultLimit = $resultLimit;
    }
}

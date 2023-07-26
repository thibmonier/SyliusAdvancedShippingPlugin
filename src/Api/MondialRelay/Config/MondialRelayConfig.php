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

final class MondialRelayConfig implements MondialRelayConfigInterface
{
    private string $url;

    private string $identifier;

    private string $key;

    private string $country = MondialRelayConfigInterface::DEFAULT_COUNTRY;

    private int $resultLimit = MondialRelayConfigInterface::DEFAULT_RESULT_LIMIT;

    private ?string $action = null;

    private ?int $defaultDelay = null;

    private ?int $searchArea = null;

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function setIdentifier(string $identifier): void
    {
        $this->identifier = $identifier;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function setKey(string $key): void
    {
        $this->key = $key;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function setCountry(string $country): void
    {
        $this->country = $country;
    }

    public function getResultLimit(): int
    {
        return $this->resultLimit;
    }

    public function setResultLimit(int $resultLimit): void
    {
        $this->resultLimit = $resultLimit;
    }

    public function getAction(): ?string
    {
        return $this->action;
    }

    public function setAction(?string $action): void
    {
        $this->action = $action;
    }

    public function getDefaultDelay(): ?int
    {
        return $this->defaultDelay;
    }

    public function setDefaultDelay(?int $defaultDelay): void
    {
        $this->defaultDelay = $defaultDelay;
    }

    public function getSearchArea(): ?int
    {
        return $this->searchArea;
    }

    public function setSearchArea(?int $searchArea): void
    {
        $this->searchArea = $searchArea;
    }
}

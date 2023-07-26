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

abstract class AbstractMapProvider
{
    private array $configuration = [];

    public function getConfiguration(): array
    {
        return $this->configuration;
    }

    public function setConfiguration(array $configuration): void
    {
        $this->configuration = $configuration;
    }

    public function getLabel(): string
    {
        return ucwords(str_replace('_', ' ', $this->getType()));
    }

    public function __toString(): string
    {
        return $this->getLabel();
    }

    abstract public function getType(): string;
}

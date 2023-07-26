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

interface MapProviderInterface
{
    public function getType(): string;

    public function getLabel(): string;

    public function setConfiguration(array $configuration): void;

    public function getUrl(): string;

    public function getParameters(): array;
}

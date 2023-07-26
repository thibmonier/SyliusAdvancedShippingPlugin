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

namespace MonsieurBiz\SyliusAdvancedShippingPlugin\Entity;

interface AdvancedShipmentMetadataAwareInterface
{
    public function getAdvancedShippingMetadata(): array;

    public function setAdvancedShippingMetadata(array $advancedShippingMetadata): void;

    public function setProviderMetadata(string $code, string $key, ?string $value): void;

    public function hasProviderMetadata(string $code, string $key): bool;

    public function getProviderMetadata(string $code, string $key): ?string;

    public function setMethodMetadata(string $code, string $key, ?string $value): void;

    public function hasMethodMetadata(string $code, string $key): bool;

    public function getMethodMetadata(string $code, string $key): ?array;
}

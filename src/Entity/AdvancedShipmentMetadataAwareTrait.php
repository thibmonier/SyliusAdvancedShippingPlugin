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

use Doctrine\ORM\Mapping as ORM;

trait AdvancedShipmentMetadataAwareTrait
{
    /**
     * @ORM\Column(name="advanced_shipping_metadata", type="array", nullable=true)
     */
    private ?array $advancedShippingMetadata = [];

    public function getAdvancedShippingMetadata(): array
    {
        return (array) $this->advancedShippingMetadata;
    }

    public function setAdvancedShippingMetadata(?array $advancedShippingMetadata): void
    {
        $this->advancedShippingMetadata = (array) $advancedShippingMetadata;
    }

    public function setProviderMetadata(string $code, string $key, ?string $value): void
    {
        $this->advancedShippingMetadata['providers'][$code][$key] = $value;
    }

    public function hasProviderMetadata(string $code, string $key): bool
    {
        return isset($this->advancedShippingMetadata['providers'][$code][$key])
            && null !== $this->advancedShippingMetadata['providers'][$code][$key];
    }

    public function getProviderMetadata(string $code, string $key): ?string
    {
        if ($this->hasProviderMetadata($code, $key)) {
            return $this->advancedShippingMetadata['providers'][$code][$key];
        }

        return null;
    }

    public function setMethodMetadata(string $code, string $key, ?string $value): void
    {
        $this->advancedShippingMetadata['methods'][$code][$key] = $value;
    }

    public function hasMethodMetadata(string $code, string $key): bool
    {
        return isset($this->advancedShippingMetadata['methods'][$code][$key])
            && null !== $this->advancedShippingMetadata['methods'][$code][$key];
    }

    public function getMethodMetadata(string $code, string $key): ?array
    {
        if ($this->hasMethodMetadata($code, $key)) {
            return $this->advancedShippingMetadata['methods'][$code][$key];
        }

        return null;
    }
}

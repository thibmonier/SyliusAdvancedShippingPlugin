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

namespace MonsieurBiz\SyliusAdvancedShippingPlugin\Model\AddressAutocomplete;

interface LocationInterface
{
    public function getType(): ?string;

    public function getIdentifier(): ?string;

    public function getName(): ?string;

    public function getAddress(): ?string;

    public function getCity(): ?string;

    public function getPostcode(): ?string;

    public function getCountryCode(): ?string;

    public function getLatitude(): ?float;

    public function getLongitude(): ?float;
}

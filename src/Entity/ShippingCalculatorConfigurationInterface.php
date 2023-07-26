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

use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TimestampableInterface;
use Sylius\Component\Resource\Model\TranslatableInterface;

interface ShippingCalculatorConfigurationInterface extends ResourceInterface, TimestampableInterface, TranslatableInterface
{
    public function getId(): int;

    public function getCalculator(): string;

    public function setCalculator(string $calculator): void;

    public function getCode(): string;

    public function setCode(string $code): void;

    public function getName(): string;

    public function setName(string $name): void;

    public function getConfiguration(): array;

    public function setConfiguration(array $configuration): void;
}

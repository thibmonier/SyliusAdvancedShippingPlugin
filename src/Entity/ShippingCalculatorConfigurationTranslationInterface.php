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
use Sylius\Component\Resource\Model\TranslationInterface;

/**
 * @SuppressWarnings(PHPMD.LongClassName)
 */
interface ShippingCalculatorConfigurationTranslationInterface extends ResourceInterface, TranslationInterface
{
    public function getId(): int;

    public function getName(): string;

    public function setName(string $name): void;
}

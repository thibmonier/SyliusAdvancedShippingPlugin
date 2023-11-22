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

use Doctrine\Common\Collections\Collection;
use Sylius\Component\Core\Model\ShippingMethodInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TranslatableInterface;

interface ShippingTypeInterface extends ResourceInterface, TranslatableInterface
{
    public function getCode(): string;

    public function setCode(?string $code): void;

    public function getLabel(): ?string;

    public function setLabel(?string $label): void;

    public function getDescription(): ?string;

    public function setDescription(?string $description): void;

    public function addMethod(ShippingMethodInterface $method): void;

    public function removeMethod(ShippingMethodInterface $method): void;

    public function hasMethod(ShippingMethodInterface $method): bool;

    public function getMethods(): Collection;
}

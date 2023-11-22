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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Sylius\Component\Core\Model\ShippingMethodInterface;
use Sylius\Component\Resource\Model\TimestampableTrait;
use Sylius\Component\Resource\Model\TranslatableTrait;

/**
 * @method ShippingTypeTranslationInterface getTranslation(?string $locale = null)
 */
class ShippingType implements ShippingTypeInterface
{
    use TimestampableTrait;
    use TranslatableTrait {
        TranslatableTrait::__construct as private initializeTranslationsCollection;
    }

    protected ?int $id;

    protected ?string $code = null;

    /**
     * @var Collection<array-key, ShippingMethodInterface>
     */
    protected Collection $methods;

    /**
     * @var \DateTimeInterface|null
     */
    protected $createdAt;

    /**
     * @var \DateTimeInterface|null
     */
    protected $updatedAt;

    public function __construct()
    {
        $this->initializeTranslationsCollection();
        $this->methods = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return (string) $this->code;
    }

    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    public function getLabel(): ?string
    {
        return $this->getTranslation()->getLabel();
    }

    public function setLabel(?string $label): void
    {
        $this->getTranslation()->setLabel($label);
    }

    public function getDescription(): ?string
    {
        return $this->getTranslation()->getDescription();
    }

    public function setDescription(?string $description): void
    {
        $this->getTranslation()->setDescription($description);
    }

    public function getMethods(): Collection
    {
        return $this->methods;
    }

    public function hasMethod(ShippingMethodInterface $method): bool
    {
        return $this->methods->contains($method);
    }

    public function addMethod(ShippingMethodInterface $method): void
    {
        if (!$this->hasMethod($method)) {
            $this->methods->add($method);
        }

        // @phpstan-ignore-next-line
        if ($this !== $method->getType()) {
            // @phpstan-ignore-next-line
            $method->setType($this);
        }
    }

    public function removeMethod(ShippingMethodInterface $method): void
    {
        if ($this->hasMethod($method)) {
            // @phpstan-ignore-next-line
            $method->setType(null);
            $this->methods->removeElement($method);
        }
    }

    protected function createTranslation(): ShippingTypeTranslationInterface
    {
        return new ShippingTypeTranslation();
    }
}

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
use Gedmo\Mapping\Annotation as Gedmo;
use Sylius\Component\Resource\Model\TimestampableTrait;
use Sylius\Component\Resource\Model\TranslatableTrait;

/**
 * @ORM\Entity
 *
 * @ORM\Table(
 *     name="monsieurbiz_shipping_calculator_config",
 *     uniqueConstraints={@ORM\UniqueConstraint(columns={"calculator", "code"})}
 * )
 *
 * @method ShippingCalculatorConfigurationTranslationInterface getTranslation(?string $locale = null)
 */
class ShippingCalculatorConfiguration implements ShippingCalculatorConfigurationInterface
{
    use TimestampableTrait;
    use TranslatableTrait {
        __construct as protected initializeTranslationsCollection;
    }

    /**
     * @ORM\Id
     *
     * @ORM\GeneratedValue
     *
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $calculator;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $code;

    /**
     * @ORM\Column(type="array")
     */
    private array $configuration = [];

    /**
     * @ORM\Column(name="created_at", type="datetime")
     *
     * @Gedmo\Timestampable(on="create")
     *
     * @var \DateTimeInterface|null
     */
    protected $createdAt;

    /**
     * @ORM\Column(name="updated_at", type="datetime")
     *
     * @Gedmo\Timestampable(on="update")
     *
     * @var \DateTimeInterface|null
     */
    protected $updatedAt;

    public function __construct()
    {
        $this->initializeTranslationsCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCalculator(): string
    {
        return $this->calculator;
    }

    public function setCalculator(string $calculator): void
    {
        $this->calculator = $calculator;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function getName(): string
    {
        return $this->getTranslation()->getName();
    }

    public function setName(string $name): void
    {
        $this->getTranslation()->setName($name);
    }

    public function getConfiguration(): array
    {
        return $this->configuration;
    }

    public function setConfiguration(array $configuration): void
    {
        $this->configuration = $configuration;
    }

    public function createTranslation(): ShippingCalculatorConfigurationTranslationInterface
    {
        return new ShippingCalculatorConfigurationTranslation();
    }
}

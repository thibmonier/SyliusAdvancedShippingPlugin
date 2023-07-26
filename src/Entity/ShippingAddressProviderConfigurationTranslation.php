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
use Sylius\Component\Resource\Model\AbstractTranslation;

/**
 * @ORM\Entity
 *
 * @ORM\Table(
 *     name="monsieurbiz_shipping_address_provider_config_translation",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="mbiz_shipping_address_provider_conf_trans_uniq_trans", columns={"translatable_id", "locale"})}
 * )
 */
class ShippingAddressProviderConfigurationTranslation extends AbstractTranslation implements ShippingAddressProviderConfigurationTranslationInterface
{
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
    private string $name;

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}

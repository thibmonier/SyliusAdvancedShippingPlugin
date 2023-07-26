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

trait ShippingTypeAwareTrait
{
    /**
     * @ORM\ManyToOne(targetEntity=\MonsieurBiz\SyliusAdvancedShippingPlugin\Entity\ShippingTypeInterface::class, inversedBy="methods")
     *
     * @ORM\JoinColumn(name="type_id", referencedColumnName="id", nullable=true)
     */
    private ?ShippingTypeInterface $type = null;

    public function getType(): ?ShippingTypeInterface
    {
        return $this->type;
    }

    public function setType(?ShippingTypeInterface $type): void
    {
        $this->type = $type;
    }
}

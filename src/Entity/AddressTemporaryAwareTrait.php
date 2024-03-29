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
use Sylius\Component\Order\Model\OrderInterface;

trait AddressTemporaryAwareTrait
{
    /**
     * @ORM\Column(type="boolean", options={"default"=false})
     */
    private bool $temporary = false;

    /**
     * @ORM\ManyToOne(targetEntity=OrderInterface::class, inversedBy="temporaryAddresses")
     * @ORM\JoinColumn(name="source_order_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private ?OrderInterface $sourceOrder = null;

    public function setTemporary(bool $isTemporary): void
    {
        $this->temporary = $isTemporary;
    }

    public function isTemporary(): bool
    {
        return $this->temporary;
    }

    public function setSourceOrder(?OrderInterface $sourceOrder): void
    {
        $this->sourceOrder = $sourceOrder;
    }

    public function getSourceOrder(): ?OrderInterface
    {
        return $this->sourceOrder;
    }
}

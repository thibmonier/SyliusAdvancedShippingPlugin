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
use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Core\Model\AddressInterface;

trait TemporaryAddressesAwareTrait
{
    /**
     * @ORM\OneToMany(targetEntity=AddressInterface::class, mappedBy="sourceOrder", cascade={"persist"})
     *
     * @var Collection<array-key, AddressInterface>
     */
    private Collection $temporaryAddresses;

    public function getTemporaryAddresses(): Collection
    {
        return $this->temporaryAddresses;
    }

    public function hasTemporaryAddress(AddressInterface $address): bool
    {
        return $this->temporaryAddresses->contains($address);
    }

    public function addMethod(AddressInterface $address): void
    {
        if (!$this->hasTemporaryAddress($address)) {
            $this->temporaryAddresses->add($address);
        }

        // @phpstan-ignore-next-line
        if ($address instanceof AddressTemporaryAwareInterface && $this !== $address->getSourceOrder()) {
            // @phpstan-ignore-next-line
            $address->setSourceOrder($this);
        }
    }

    public function removeMethod(AddressInterface $address): void
    {
        if ($address instanceof AddressTemporaryAwareInterface && $this->hasTemporaryAddress($address)) {
            $address->setSourceOrder(null);
            $this->temporaryAddresses->removeElement($address);
        }
    }
}

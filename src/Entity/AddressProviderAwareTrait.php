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

trait AddressProviderAwareTrait
{
    /**
     * @ORM\ManyToOne(targetEntity=\MonsieurBiz\SyliusAdvancedShippingPlugin\Entity\ShippingAddressProviderConfiguration::class)
     *
     * @ORM\JoinColumn(name="shipping_address_provider_configuration_id", referencedColumnName="id", nullable=true)
     */
    private ?ShippingAddressProviderConfigurationInterface $shippingAddressProviderConfiguration = null;

    public function getShippingAddressProviderConfiguration(): ?ShippingAddressProviderConfigurationInterface
    {
        return $this->shippingAddressProviderConfiguration;
    }

    public function setShippingAddressProviderConfiguration(?ShippingAddressProviderConfigurationInterface $shippingAddressProviderConfiguration): void
    {
        $this->shippingAddressProviderConfiguration = $shippingAddressProviderConfiguration;
    }
}

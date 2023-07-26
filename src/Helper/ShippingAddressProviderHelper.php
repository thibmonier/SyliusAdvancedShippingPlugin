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

namespace MonsieurBiz\SyliusAdvancedShippingPlugin\Helper;

use MonsieurBiz\SyliusAdvancedShippingPlugin\Entity\AddressProviderAwareInterface;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Factory\AddressProviderFactoryInterface;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Provider\ShippingAddressProvider\ShippingAddressProviderInterface;
use Sylius\Component\Core\Model\ShippingMethodInterface;

final class ShippingAddressProviderHelper implements ShippingAddressProviderHelperInterface
{
    private AddressProviderFactoryInterface $addressProviderFactory;

    public function __construct(AddressProviderFactoryInterface $addressProviderFactory)
    {
        $this->addressProviderFactory = $addressProviderFactory;
    }

    public function getProviderByMethod(ShippingMethodInterface $method): ?ShippingAddressProviderInterface
    {
        if (!$method instanceof AddressProviderAwareInterface) {
            return null;
        }
        $configuration = $method->getShippingAddressProviderConfiguration();

        return null === $configuration ? null : $this->addressProviderFactory->createFromConfiguration($configuration);
    }
}

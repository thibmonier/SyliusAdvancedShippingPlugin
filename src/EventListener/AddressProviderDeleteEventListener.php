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

namespace MonsieurBiz\SyliusAdvancedShippingPlugin\EventListener;

use MonsieurBiz\SyliusAdvancedShippingPlugin\Entity\ShippingAddressProviderConfigurationInterface;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Webmozart\Assert\Assert;

final class AddressProviderDeleteEventListener
{
    private RepositoryInterface $shippingMethodRepository;

    public function __construct(RepositoryInterface $shippingMethodRepository)
    {
        $this->shippingMethodRepository = $shippingMethodRepository;
    }

    public function __invoke(ResourceControllerEvent $event): void
    {
        /** @var ShippingAddressProviderConfigurationInterface $provider */
        $provider = $event->getSubject();
        Assert::isInstanceOf($provider, ShippingAddressProviderConfigurationInterface::class);

        $shippingMethods = $this->shippingMethodRepository->findBy(['shippingAddressProviderConfiguration' => $provider]);
        if (0 === \count($shippingMethods)) {
            return;
        }

        $event->stopPropagation();
        $event->setMessage('monsieurbiz_advanced_shipping.address_provider.delete_used');
        $event->setMessageType(ResourceControllerEvent::TYPE_ERROR);
    }
}

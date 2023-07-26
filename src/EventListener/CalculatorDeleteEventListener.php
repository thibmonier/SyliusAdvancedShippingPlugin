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

use App\Entity\Shipping\ShippingMethodInterface;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Entity\ShippingCalculatorConfigurationInterface;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Webmozart\Assert\Assert;

final class CalculatorDeleteEventListener
{
    private RepositoryInterface $shippingMethodRepository;

    public function __construct(RepositoryInterface $shippingMethodRepository)
    {
        $this->shippingMethodRepository = $shippingMethodRepository;
    }

    public function __invoke(ResourceControllerEvent $event): void
    {
        /** @var ShippingCalculatorConfigurationInterface $calculator */
        $calculator = $event->getSubject();
        Assert::isInstanceOf($calculator, ShippingCalculatorConfigurationInterface::class);

        $shippingMethods = $this->shippingMethodRepository->findAll();
        /** @var ShippingMethodInterface $shippingMethod */
        foreach ($shippingMethods as $shippingMethod) {
            if (
                $calculator->getCode() === $shippingMethod->getCalculator()
                || \in_array($calculator->getId(), $shippingMethod->getConfiguration()['calculators'] ?? [], true)
            ) {
                $event->stopPropagation();
                $event->setMessage('monsieurbiz_advanced_shipping.calculator.delete_used');
                $event->setMessageType(ResourceControllerEvent::TYPE_ERROR);

                return;
            }
        }
    }
}

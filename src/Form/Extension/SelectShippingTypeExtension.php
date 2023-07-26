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

namespace MonsieurBiz\SyliusAdvancedShippingPlugin\Form\Extension;

use MonsieurBiz\SyliusAdvancedShippingPlugin\Entity\AddressTemporaryAwareInterface;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Processor\AdvancedShippingProcessor;
use Sylius\Bundle\CoreBundle\Form\Type\Checkout\SelectShippingType;
use Sylius\Component\Core\Model\OrderInterface;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Webmozart\Assert\Assert;

final class SelectShippingTypeExtension extends AbstractTypeExtension
{
    public function __construct(private AdvancedShippingProcessor $advancedShippingProcessor)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event): void {
                /** @var OrderInterface $order */
                $order = $event->getData();
                Assert::isInstanceOf($order, OrderInterface::class);

                // Use original address to display the customer address in form.
                $shippingAddress = $order->getShippingAddress();
                if ($shippingAddress instanceof AddressTemporaryAwareInterface && $shippingAddress->isTemporary() && $order->hasShipments()) {
                    $order->setShippingAddress($this->advancedShippingProcessor->getOriginalAddress($order->getShipments()->last()));
                }
            })
            ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event): void {
                /** @var OrderInterface $order */
                $order = $event->getData();
                Assert::isInstanceOf($order, OrderInterface::class);

                $this->advancedShippingProcessor->process($order);
            })
        ;
    }

    public static function getExtendedTypes(): iterable
    {
        return [
            SelectShippingType::class,
        ];
    }
}

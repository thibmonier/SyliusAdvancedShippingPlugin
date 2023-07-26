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

use MonsieurBiz\SyliusAdvancedShippingPlugin\Form\Type\AdvancedShippingMetadataType;
use Sylius\Bundle\CoreBundle\Form\Type\Checkout\ShipmentType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

final class ShipmentTypeExtension extends AbstractTypeExtension
{
    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event): void {
                $form = $event->getForm();
                $shipment = $event->getData();

                $form->add('advancedShippingMetadata', AdvancedShippingMetadataType::class, [
                    'label' => false,
                    'shipment' => $shipment,
                ]);
            })
        ;
    }

    public static function getExtendedTypes(): iterable
    {
        return [ShipmentType::class];
    }
}

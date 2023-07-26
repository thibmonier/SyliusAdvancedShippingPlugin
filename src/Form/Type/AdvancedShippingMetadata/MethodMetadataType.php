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

namespace MonsieurBiz\SyliusAdvancedShippingPlugin\Form\Type\AdvancedShippingMetadata;

use Sylius\Component\Shipping\Resolver\ShippingMethodsResolverInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class MethodMetadataType extends AbstractType
{
    private ShippingMethodsResolverInterface $shippingMethodsResolver;

    public function __construct(ShippingMethodsResolverInterface $shippingMethodsResolver)
    {
        $this->shippingMethodsResolver = $shippingMethodsResolver;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        foreach ($this->shippingMethodsResolver->getSupportedMethods($options['shipment']) as $key => $method) {
            $builder
                ->add($method->getCode() ?? 'method_' . $key, MetadataType::class, [
                    'label' => false,
                    'shipping_method' => $method,
                    'shipment' => $options['shipment'],
                ])
            ;
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefined(['shipment']);
    }
}

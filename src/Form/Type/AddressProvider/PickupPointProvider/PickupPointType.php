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

namespace MonsieurBiz\SyliusAdvancedShippingPlugin\Form\Type\AddressProvider\PickupPointProvider;

use MonsieurBiz\SyliusAdvancedShippingPlugin\Form\DataTransformer\JsonToPickupPointTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class PickupPointType extends AbstractType
{
    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addModelTransformer(new JsonToPickupPointTransformer());
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('attr', ['data-target' => 'pickup-point-selected-value']);
    }

    public function getParent(): string
    {
        return HiddenType::class;
    }
}

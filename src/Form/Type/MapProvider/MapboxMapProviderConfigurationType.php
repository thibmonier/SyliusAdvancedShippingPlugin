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

namespace MonsieurBiz\SyliusAdvancedShippingPlugin\Form\Type\MapProvider;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

final class MapboxMapProviderConfigurationType extends AbstractType
{
    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('accessToken', TextType::class, [
                'label' => 'monsieurbiz_advanced_shipping.form.mapbox_map_provider.access_token',
                'constraints' => [
                    new Assert\NotBlank([
                        'groups' => ['mapbox_map_provider'],
                    ]),
                ],
            ])
            ->add('attribution', TextType::class, [
                'required' => false,
                'label' => 'monsieurbiz_advanced_shipping.form.mapbox_map_provider.attribution',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'validation_groups' => ['mapbox_map_provider'],
        ]);
    }
}

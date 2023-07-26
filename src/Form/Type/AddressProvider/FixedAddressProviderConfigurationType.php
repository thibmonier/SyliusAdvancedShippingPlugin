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

namespace MonsieurBiz\SyliusAdvancedShippingPlugin\Form\Type\AddressProvider;

use Sylius\Bundle\AddressingBundle\Form\Type\CountryCodeChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

final class FixedAddressProviderConfigurationType extends AbstractType
{
    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('code', TextType::class, [
                'label' => 'monsieurbiz_advanced_shipping.form.fixed_shipping_address_provider.code',
                'constraints' => [
                    new Assert\NotBlank([
                        'groups' => ['fixed_address_provider'],
                    ]),
                ],
            ])
            /** @TODO make it translatable */
            ->add('title', TextType::class, [
                'label' => 'monsieurbiz_advanced_shipping.form.fixed_shipping_address_provider.title',
                'constraints' => [
                    new Assert\NotBlank([
                        'groups' => ['fixed_address_provider'],
                    ]),
                ],
            ])
            /** @TODO make it translatable */
            ->add('instructions', TextareaType::class, [
                'label' => 'monsieurbiz_advanced_shipping.form.fixed_shipping_address_provider.instructions',
                'constraints' => [
                    new Assert\NotBlank([
                        'groups' => ['fixed_address_provider'],
                    ]),
                ],
            ])
            ->add('company', TextType::class, [
                'required' => false,
                'label' => 'monsieurbiz_advanced_shipping.form.fixed_shipping_address_provider.company',
            ])
            ->add('street', TextType::class, [
                'label' => 'monsieurbiz_advanced_shipping.form.fixed_shipping_address_provider.street',
                'constraints' => [
                    new Assert\NotBlank([
                        'groups' => ['fixed_address_provider'],
                    ]),
                ],
            ])
            ->add('city', TextType::class, [
                'label' => 'monsieurbiz_advanced_shipping.form.fixed_shipping_address_provider.city',
                'constraints' => [
                    new Assert\NotBlank([
                        'groups' => ['fixed_address_provider'],
                    ]),
                ],
            ])
            ->add('postcode', TextType::class, [
                'label' => 'monsieurbiz_advanced_shipping.form.fixed_shipping_address_provider.postcode',
                'constraints' => [
                    new Assert\NotBlank([
                        'groups' => ['fixed_address_provider'],
                    ]),
                ],
            ])
            ->add('countryCode', CountryCodeChoiceType::class, [
                'label' => 'monsieurbiz_advanced_shipping.form.fixed_shipping_address_provider.country',
                'enabled' => true,
                'constraints' => [
                    new Assert\NotBlank([
                        'groups' => ['fixed_address_provider'],
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('validation_groups', ['fixed_address_provider']);
    }

    public function getBlockPrefix()
    {
        return 'monsieurbiz_advanced_shipment_address_provider_fixed';
    }
}

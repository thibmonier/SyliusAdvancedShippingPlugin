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

namespace MonsieurBiz\SyliusAdvancedShippingPlugin\Form\Type\AddressProvider\ColisPrivePickupAddressProviderConfiguration;

use MonsieurBiz\SyliusAdvancedShippingPlugin\Api\ColisPrivePickup\Config\ColisPrivePickupConfig;
use Sylius\Bundle\AddressingBundle\Form\Type\CountryCodeChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

final class ColisPrivePickupApiConfigurationType extends AbstractType
{
    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('apiUrl', UrlType::class, [
                'label' => 'monsieurbiz_advanced_shipping.form.colis_prive_pickup_shipping_address_provider.url',
                'constraints' => [
                    new Assert\NotBlank([
                        'groups' => ['colis_prive_pickup_address_provider'],
                    ]),
                ],
            ])
            ->add('accountId', TextType::class, [
                'label' => 'monsieurbiz_advanced_shipping.form.colis_prive_pickup_shipping_address_provider.account_id',
                'constraints' => [
                    new Assert\NotBlank([
                        'groups' => ['colis_prive_pickup_address_provider'],
                    ]),
                ],
            ])
            ->add('countryCode', CountryCodeChoiceType::class, [
                'label' => 'monsieurbiz_advanced_shipping.form.colis_prive_pickup_shipping_address_provider.country_code',
                'constraints' => [
                    new Assert\NotBlank([
                        'groups' => ['colis_prive_pickup_address_provider'],
                    ]),
                ],
            ])
            ->add('defaultDelay', IntegerType::class, [
                'required' => false,
                'label' => 'monsieurbiz_advanced_shipping.form.colis_prive_pickup_shipping_address_provider.default_delay',
                'constraints' => [
                    new Assert\Type([
                        'type' => 'integer',
                        'groups' => ['colis_prive_pickup_address_provider'],
                    ]),
                    new Assert\Range([
                        'min' => 0,
                        'groups' => ['colis_prive_pickup_address_provider'],
                    ]),
                ],
            ])
            ->add('resultLimit', IntegerType::class, [
                'label' => 'monsieurbiz_advanced_shipping.form.colis_prive_pickup_shipping_address_provider.result_limit',
                'constraints' => [
                    new Assert\NotBlank([
                        'groups' => ['colis_prive_pickup_address_provider'],
                    ]),
                    new Assert\Type([
                        'type' => 'integer',
                        'groups' => ['colis_prive_pickup_address_provider'],
                    ]),
                    new Assert\Range([
                        'min' => 1,
                        'max' => 30,
                        'groups' => ['colis_prive_pickup_address_provider'],
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('data_class', ColisPrivePickupConfig::class);
    }
}

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

namespace MonsieurBiz\SyliusAdvancedShippingPlugin\Form\Type\AddressProvider\ChronopostPickupAddressProviderConfiguration;

use MonsieurBiz\SyliusAdvancedShippingPlugin\Api\ChronopostPickup\Config\ChronopostPickupConfig;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

final class ChronopostPickupApiConfigurationType extends AbstractType
{
    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pickupApiUrl', UrlType::class, [
                'label' => 'monsieurbiz_advanced_shipping.form.chronopost_pickup_shiping_address_provider.api_url',
                'constraints' => [
                    new Assert\NotBlank([
                        'groups' => ['chronopost_pickup_address_provider'],
                    ]),
                ],
            ])
            ->add('pickupApiAccountNumber', TextType::class, [
                'label' => 'monsieurbiz_advanced_shipping.form.chronopost_pickup_shiping_address_provider.api_account_number',
                'constraints' => [
                    new Assert\NotBlank([
                        'groups' => ['chronopost_pickup_address_provider'],
                    ]),
                ],
            ])
            ->add('pickupApiPassword', TextType::class, [
                'label' => 'monsieurbiz_advanced_shipping.form.chronopost_pickup_shiping_address_provider.api_password',
                'constraints' => [
                    new Assert\NotBlank([
                        'groups' => ['chronopost_pickup_address_provider'],
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('data_class', ChronopostPickupConfig::class);
    }
}

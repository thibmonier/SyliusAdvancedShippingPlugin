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

use MonsieurBiz\SyliusAdvancedShippingPlugin\Api\ChronopostPickup\Config\ChronopostPickupConfigInterface;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Form\Type\AddressProvider\ChronopostPickupAddressProviderConfiguration\ChronopostPickupApiConfigurationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

final class ChronopostPickupAddressProviderConfigurationType extends AbstractType
{
    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('apiConfiguration', ChronopostPickupApiConfigurationType::class, [
                'label' => false,
                'validation_groups' => ['chronopost_pickup_address_provider', 'pickup_point_address_provider'],
                'constraints' => [
                    new Assert\Valid([
                        'groups' => ['chronopost_pickup_address_provider'],
                    ]),
                ],
                // We had to clone configuration object because instead modifications are not detected by Doctrine
                'setter' => function (array &$config, ChronopostPickupConfigInterface $chronopostPickupConfig): void {
                    $config['apiConfiguration'] = clone $chronopostPickupConfig;
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('validation_groups', ['chronopost_pickup_address_provider']);
    }

    public function getBlockPrefix(): string
    {
        return 'monsieurbiz_advanced_shipment_address_chronopost_pickup_fixed';
    }

    public function getParent()
    {
        return PickupPointProviderConfigurationType::class;
    }
}

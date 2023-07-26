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

namespace MonsieurBiz\SyliusAdvancedShippingPlugin\Form\Type\AddressProvider\MondialRelayAddressProviderConfiguration;

use MonsieurBiz\SyliusAdvancedShippingPlugin\Api\MondialRelay\Config\MondialRelayConfig;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Api\MondialRelay\Config\MondialRelayConfigInterface;
use Sylius\Bundle\AddressingBundle\Form\Type\CountryCodeChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

final class MondialRelayApiConfigurationType extends AbstractType
{
    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $actions = [];
        foreach (MondialRelayConfigInterface::ACTIONS as $action) {
            $actions['monsieurbiz_advanced_shipping.mondial_relay.actions.' . strtolower($action)] = $action;
        }
        $builder
            ->add('url', UrlType::class, [
                'label' => 'monsieurbiz_advanced_shipping.form.mondial_relay_shiping_address_provider.url',
                'constraints' => [
                    new Assert\NotBlank([
                        'groups' => ['mondial_relay_address_provider'],
                    ]),
                ],
            ])
            ->add('identifier', TextType::class, [
                'label' => 'monsieurbiz_advanced_shipping.form.mondial_relay_shiping_address_provider.identifier',
                'constraints' => [
                    new Assert\NotBlank([
                        'groups' => ['mondial_relay_address_provider'],
                    ]),
                ],
            ])
            ->add('key', TextType::class, [
                'label' => 'monsieurbiz_advanced_shipping.form.mondial_relay_shiping_address_provider.key',
                'constraints' => [
                    new Assert\NotBlank([
                        'groups' => ['mondial_relay_address_provider'],
                    ]),
                ],
            ])
            ->add('country', CountryCodeChoiceType::class, [
                'label' => 'monsieurbiz_advanced_shipping.form.mondial_relay_shiping_address_provider.country',
                'constraints' => [
                    new Assert\NotBlank([
                        'groups' => ['mondial_relay_address_provider'],
                    ]),
                ],
            ])
            ->add('resultLimit', IntegerType::class, [
                'label' => 'monsieurbiz_advanced_shipping.form.mondial_relay_shiping_address_provider.result_limit',
                'constraints' => [
                    new Assert\NotBlank([
                        'groups' => ['mondial_relay_address_provider'],
                    ]),
                    new Assert\Type([
                        'type' => 'integer',
                        'groups' => ['mondial_relay_address_provider'],
                    ]),
                    new Assert\Range([
                        'min' => 1,
                        'max' => 30,
                        'groups' => ['mondial_relay_address_provider'],
                    ]),
                ],
            ])
            ->add('action', ChoiceType::class, [
                'required' => false,
                'label' => 'monsieurbiz_advanced_shipping.form.mondial_relay_shiping_address_provider.action',
                'choices' => $actions,
            ])
            ->add('defaultDelay', IntegerType::class, [
                'required' => false,
                'label' => 'monsieurbiz_advanced_shipping.form.mondial_relay_shiping_address_provider.default_delay',
                'constraints' => [
                    new Assert\Type([
                        'type' => 'integer',
                        'groups' => ['mondial_relay_address_provider'],
                    ]),
                    new Assert\Range([
                        'min' => 0,
                        'groups' => ['mondial_relay_address_provider'],
                    ]),
                ],
            ])
            ->add('searchArea', IntegerType::class, [
                'required' => false,
                'label' => 'monsieurbiz_advanced_shipping.form.mondial_relay_shiping_address_provider.search_area',
                'constraints' => [
                    new Assert\Type([
                        'type' => 'integer',
                        'groups' => ['mondial_relay_address_provider'],
                    ]),
                    new Assert\Range([
                        'min' => 0,
                        'groups' => ['mondial_relay_address_provider'],
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('data_class', MondialRelayConfig::class);
    }
}

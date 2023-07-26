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

use MonsieurBiz\SyliusAdvancedShippingPlugin\Form\Type\AddressProvider\PickupPointProvider\PickupPointType;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Form\Type\AddressSearchAutocompleteType;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Form\Type\AdvancedShippingMetadata\AbstractAdvancedShippingMetadataType;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Helper\ShippingAddressProviderHelperInterface;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Provider\AddressAutocompleteProvider\OpenStreetMapAutocompleteProvider;
use Sylius\Component\Core\Model\ShipmentInterface;
use Sylius\Component\Core\Model\ShippingMethodInterface;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

final class PickupPointProviderMetadataType extends AbstractAdvancedShippingMetadataType
{
    private ShippingAddressProviderHelperInterface $helper;

    public function __construct(ShippingAddressProviderHelperInterface $helper)
    {
        $this->helper = $helper;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $firstName = $lastName = null;
        if (true === isset($options['shipment'])) {
            /** @var ShipmentInterface $shipment */
            $shipment = $options['shipment'];
            if (null !== $shipment->getOrder() && null !== $shipment->getOrder()->getBillingAddress()) {
                /** @TODO maybe retrieve the default shipping address data */
                $firstName = $shipment->getOrder()->getBillingAddress()->getFirstName();
                $lastName = $shipment->getOrder()->getBillingAddress()->getLastName();
            }
        }

        $builder
            ->add('location', AddressSearchAutocompleteType::class, [
                'label' => false,
                'provider' => OpenStreetMapAutocompleteProvider::TYPE,
                'limit' => 1,
                'identifier' => $options['identifier'],
                'replace_value' => false,
                'display_sugestions' => false,
                'use_button' => true,
                'display_no_result_error_message' => true,
            ])
            ->add('firstName', HiddenType::class, [
                'label' => false,
                'data' => $firstName,
            ])
            ->add('lastName', HiddenType::class, [
                'label' => false,
                'data' => $lastName,
            ])
            ->add('pickupPoint', PickupPointType::class, [
                'label' => false,
                'error_bubbling' => false,
                'constraints' => [
                    new Assert\NotBlank([
                        'groups' => [$options['provider_validation_group'] ?? null],
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('validation_groups', function (FormInterface $form) {
            $shipment = $form->getConfig()->getOption('shipment');
            if (!$shipment instanceof ShipmentInterface) {
                return [];
            }

            $method = $shipment->getMethod();
            if (!$method instanceof ShippingMethodInterface) {
                return [];
            }

            $provider = $this->helper->getProviderByMethod($method);
            if (null === $provider) {
                return [];
            }

            return ['provider_' . $provider->getType()];
        });
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['pickup_point'] = $form->get('pickupPoint')->getData();
    }
}

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

namespace MonsieurBiz\SyliusAdvancedShippingPlugin\Form\Type;

use Sylius\Component\Core\Model\OrderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class AddressSearchAutocompleteType extends AbstractType
{
    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('search', TextType::class, [
                'label' => false,
            ])
            ->add('location', HiddenType::class, [
                'label' => false,
            ])
            ->add('country', HiddenType::class, [
                'label' => false,
            ])
        ;

        // Prefill the search input with the shipping address postcode
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event): void {
            $rootForm = $event->getForm()->getRoot();
            if ($rootForm === $event->getForm()) {
                // Prevent cycle error on PRE_SET_DATA if has no root form.
                return;
            }
            $order = $rootForm->getData();
            if (!$order instanceof OrderInterface) {
                return;
            }

            $defaultSearchLocation = $order->getShippingAddress()?->getPostcode();
            $defaultCountryCode = $order->getShippingAddress()?->getCountryCode();
            $data = $event->getData() ?? [];
            $event->setData(['search' => $defaultSearchLocation, 'country' => $defaultCountryCode] + $data);
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('provider');
        $resolver->setAllowedTypes('provider', ['string']);
        $resolver->setRequired('identifier');
        $resolver->setAllowedTypes('identifier', ['string']);
        $resolver->setDefault('limit', 5);
        $resolver->setAllowedTypes('limit', ['integer']);
        $resolver->setDefault('replace_value', true);
        $resolver->setAllowedTypes('replace_value', ['bool']);
        $resolver->setDefault('display_sugestions', true);
        $resolver->setAllowedTypes('display_sugestions', ['bool']);
        $resolver->setDefault('use_button', false);
        $resolver->setAllowedTypes('use_button', ['bool']);
        $resolver->setDefault('button_label', 'monsieurbiz_advanced_shipping.ui.search_button');
        $resolver->setAllowedTypes('button_label', ['string']);
        $resolver->setDefault('display_no_result_error_message', false);
        $resolver->setAllowedTypes('use_button', ['bool']);
        $resolver->setDefault('no_result_error_message', 'monsieurbiz_advanced_shipping.ui.no_result');
        $resolver->setAllowedTypes('button_label', ['string']);

        $resolver->setDefault('allow_extra_fields', true);
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['provider'] = $options['provider'];
        $view->vars['limit'] = $options['limit'];
        $view->vars['identifier'] = $options['identifier'];
        $view->vars['replace_value'] = $options['replace_value'];
        $view->vars['display_sugestions'] = $options['display_sugestions'];
        $view->vars['use_button'] = $options['use_button'];
        $view->vars['button_label'] = $options['button_label'];
        $view->vars['has_no_result_error_message'] = $options['display_no_result_error_message'];
        $view->vars['no_result_error_message'] = $options['no_result_error_message'];
    }

    public function getBlockPrefix(): string
    {
        return 'monsieurbiz_advance_shipping_address_search_autocomplete';
    }
}

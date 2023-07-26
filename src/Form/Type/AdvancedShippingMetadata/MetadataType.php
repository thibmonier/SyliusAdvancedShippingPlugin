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

use MonsieurBiz\SyliusAdvancedShippingPlugin\Entity\AddressProviderAwareInterface;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Factory\AddressProviderFactoryInterface;
use Sylius\Component\Core\Model\ShipmentInterface;
use Sylius\Component\Core\Model\ShippingMethodInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class MetadataType extends AbstractType
{
    private AddressProviderFactoryInterface $addressProviderFactory;

    public function __construct(AddressProviderFactoryInterface $addressProviderFactory)
    {
        $this->addressProviderFactory = $addressProviderFactory;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $method = $options['shipping_method'];
        $shipment = $options['shipment'];
        $this->addShippingAddressProviderFormType($builder, $shipment, $method);
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $method = $options['shipping_method'];
        $view->vars['method'] = $method;
        $view->vars['shipment'] = $options['shipment'];
        $this->addShippingAddressProviderFormViewVars($view, $method);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefined(['shipping_method', 'shipment']);
    }

    private function addShippingAddressProviderFormType(FormBuilderInterface $builder, ShipmentInterface $shipment, ShippingMethodInterface $method): void
    {
        if ($method instanceof AddressProviderAwareInterface && null !== $method->getShippingAddressProviderConfiguration()) {
            $provider = $this->addressProviderFactory->createFromConfiguration($method->getShippingAddressProviderConfiguration());
            if (null !== $provider->getShipmentMetadataFormType()) {
                $builder->add('addressProvider', $provider->getShipmentMetadataFormType(), [
                    'label' => false,
                    'shipment' => $shipment,
                ]);
            }
        }
    }

    private function addShippingAddressProviderFormViewVars(FormView $view, ShippingMethodInterface $method): void
    {
        if ($method instanceof AddressProviderAwareInterface && null !== $method->getShippingAddressProviderConfiguration()) {
            $provider = $this->addressProviderFactory->createFromConfiguration($method->getShippingAddressProviderConfiguration());
            $view->vars['address_provider_template'] = $provider->getShipmentMetadataTemplate();
            $view->vars['address_provider'] = $provider;
        }
    }
}

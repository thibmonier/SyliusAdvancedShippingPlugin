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

use Sylius\Bundle\ResourceBundle\Form\Registry\FormTypeRegistryInterface;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Bundle\ResourceBundle\Form\Type\ResourceTranslationsType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

final class AddressProviderConfigurationType extends AbstractResourceType
{
    private array $providers;

    private FormTypeRegistryInterface $formTypeRegistry;

    public function __construct(
        array $providers,
        FormTypeRegistryInterface $formTypeRegistry,
        string $dataClass,
        array $validationGroups = []
    ) {
        parent::__construct($dataClass, $validationGroups);
        $this->providers = $providers;
        $this->formTypeRegistry = $formTypeRegistry;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('code', TextType::class, [
                'label' => 'monsieurbiz_advanced_shipping.form.code',
            ])
            ->add('provider', ChoiceType::class, [
                'label' => 'monsieurbiz_advanced_shipping.form.provider',
                'disabled' => true,
                'choices' => array_flip($this->providers),
            ])
            ->add('configuration', $this->formTypeRegistry->get($options['data']->getProvider(), 'default'), [
                'label' => false,
            ])
            ->add('translations', ResourceTranslationsType::class, [
                'label' => 'monsieurbiz_advanced_shipping.form.translations',
                'entry_type' => AddressProviderConfigurationTranslationType::class,
            ])
        ;
    }
}

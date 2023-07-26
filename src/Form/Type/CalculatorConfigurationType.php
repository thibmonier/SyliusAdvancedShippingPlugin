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

use MonsieurBiz\SyliusAdvancedShippingPlugin\Calculator\ChainedCalculator;
use Sylius\Bundle\ResourceBundle\Form\Registry\FormTypeRegistryInterface;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Bundle\ResourceBundle\Form\Type\ResourceTranslationsType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

final class CalculatorConfigurationType extends AbstractResourceType
{
    private array $calculators;

    private FormTypeRegistryInterface $formTypeRegistry;

    public function __construct(
        array $calculators,
        FormTypeRegistryInterface $formTypeRegistry,
        string $dataClass,
        array $validationGroups = []
    ) {
        parent::__construct($dataClass, $validationGroups);
        unset($calculators[ChainedCalculator::TYPE]);
        $this->calculators = $calculators;
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
            ->add('calculator', ChoiceType::class, [
                'label' => 'monsieurbiz_advanced_shipping.form.calculator',
                'disabled' => true,
                'choices' => array_flip($this->calculators),
            ])
            ->add('configuration', $this->formTypeRegistry->get($options['data']->getCalculator(), 'default'), [
                'label' => false,
            ])
            ->add('translations', ResourceTranslationsType::class, [
                'label' => 'monsieurbiz_advanced_shipping.form.translations',
                'entry_type' => CalculatorConfigurationTranslationType::class,
            ])
        ;
    }
}

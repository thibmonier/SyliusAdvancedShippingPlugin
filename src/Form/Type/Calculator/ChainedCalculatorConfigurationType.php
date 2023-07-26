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

namespace MonsieurBiz\SyliusAdvancedShippingPlugin\Form\Type\Calculator;

use MonsieurBiz\SyliusAdvancedShippingPlugin\Entity\ShippingCalculatorConfigurationInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;

final class ChainedCalculatorConfigurationType extends AbstractType
{
    private RepositoryInterface $shippingCalculatorConfigurationRepository;

    public function __construct(RepositoryInterface $shippingCalculatorConfigurationRepository)
    {
        $this->shippingCalculatorConfigurationRepository = $shippingCalculatorConfigurationRepository;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $choice = [];
        /** @var ShippingCalculatorConfigurationInterface $configuration */
        foreach ($this->shippingCalculatorConfigurationRepository->findAll() as $configuration) {
            $key = sprintf('%s - %s', $configuration->getCode(), $configuration->getName());
            $choice[$configuration->getCalculator()][$key] = $configuration->getId();
        }

        $builder
            ->add('calculators', CollectionType::class, [
                'label' => 'monsieurbiz_advanced_shipping.form.chained_calculator_configuration.calculators',
                'button_add_label' => 'monsieurbiz_advanced_shipping.form.chained_calculator_configuration.add_calculator',
                'entry_type' => ChoiceType::class,
                'entry_options' => ['choices' => $choice],
                'allow_add' => true,
                'allow_delete' => true,
                'delete_empty' => true,
            ])
        ;
    }
}

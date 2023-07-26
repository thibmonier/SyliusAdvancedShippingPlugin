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

namespace MonsieurBiz\SyliusAdvancedShippingPlugin\Form\Type\Calculator\TableRateCalculatorConfiguration;

use Sylius\Bundle\MoneyBundle\Form\Type\MoneyType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

final class BaseTableRateConfigurationType extends AbstractType
{
    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('defaultRate', MoneyType::class, [
                'label' => 'monsieurbiz_advanced_shipping.form.table_rate_calculator_configuration.default_rate',
                'priority' => 200,
                'validation_groups' => ['table_rate_calculator'],
                'constraints' => [
                    new Assert\NotBlank([
                        'groups' => ['table_rate_calculator'],
                    ]),
                ],
            ])
            ->add('rateTable', CollectionType::class, [
                'label' => 'monsieurbiz_advanced_shipping.form.table_rate_calculator_configuration.rate_table',
                'entry_type' => $options['collection_type'],
                'entry_options' => ['validation_groups' => 'table_rate_calculator'],
                'allow_add' => true,
                'allow_delete' => true,
                'delete_empty' => true,
                'error_bubbling' => false,
                'validation_groups' => ['table_rate_calculator'],
                'constraints' => [
                    new Assert\NotBlank([
                        'groups' => ['table_rate_calculator'],
                    ]),
                    new Assert\Valid([
                        'groups' => ['table_rate_calculator'],
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'validation_groups' => ['table_rate_calculator'],
            'collection_type' => null,
        ]);
    }
}

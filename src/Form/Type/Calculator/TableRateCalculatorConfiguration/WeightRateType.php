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
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class WeightRateType extends AbstractType
{
    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('limit', NumberType::class, [
                'label' => 'monsieurbiz_advanced_shipping.form.table_rate_calculator_configuration.weight_limit',
                'validation_groups' => 'table_rate_calculator',
                'constraints' => [
                    new Assert\NotBlank([
                        'groups' => ['table_rate_calculator'],
                    ]),
                ],
            ])
            ->add('rate', MoneyType::class, [
                'label' => 'monsieurbiz_advanced_shipping.form.table_rate_calculator_configuration.rate',
                'validation_groups' => 'table_rate_calculator',
                'constraints' => [
                    new Assert\NotBlank([
                        'groups' => ['table_rate_calculator'],
                    ]),
                ],
            ])
        ;
    }
}

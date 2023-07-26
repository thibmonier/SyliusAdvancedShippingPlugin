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

use MonsieurBiz\SyliusAdvancedShippingPlugin\Form\Type\Calculator\TableRateCalculatorConfiguration\BaseTableRateConfigurationType;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Form\Type\Calculator\TableRateCalculatorConfiguration\TotalRateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class TotalBasedTableRateCalculatorConfigurationType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'collection_type' => TotalRateType::class,
        ]);
    }

    public function getParent(): string
    {
        return BaseTableRateConfigurationType::class;
    }
}

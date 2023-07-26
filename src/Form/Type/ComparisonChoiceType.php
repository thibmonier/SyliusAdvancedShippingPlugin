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

use MonsieurBiz\SyliusAdvancedShippingPlugin\Checker\ComparisonType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ComparisonChoiceType extends AbstractType
{
    public function getParent(): string
    {
        return ChoiceType::class;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('choices', [
            'monsieurbiz_advanced_shipping.form.choice_type.' . ComparisonType::INCLUDED => ComparisonType::INCLUDED,
            'monsieurbiz_advanced_shipping.form.choice_type.' . ComparisonType::EXCLUDED => ComparisonType::EXCLUDED,
        ]);
    }
}

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

namespace MonsieurBiz\SyliusAdvancedShippingPlugin\Form\Type\Calculator\PostcodeCalculatorConfiguration;

use MonsieurBiz\SyliusAdvancedShippingPlugin\Calculator\PostcodeCalculator;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Form\Type\Calculator\PostcodeCalculatorConfigurationType as Base;
use Sylius\Bundle\MoneyBundle\Form\Type\MoneyType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class RuleType extends AbstractType
{
    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('comparisonType', ChoiceType::class, [
                'label' => Base::TRANS_KEY . '.comparison_type',
                'choices' => [
                    Base::TRANS_KEY . '.included' => PostcodeCalculator::$comparisonTypeIncluded,
                    Base::TRANS_KEY . '.excluded' => PostcodeCalculator::$comparisonTypeExcluded,
                ],
                'validation_groups' => [Base::VALIDATION_GROUP],
                'constraints' => [
                    new Assert\NotBlank([
                        'groups' => [Base::VALIDATION_GROUP],
                    ]),
                ],
            ])
            ->add('rule', TextareaType::class, [
                'label' => Base::TRANS_KEY . '.rule',
                'validation_groups' => [Base::VALIDATION_GROUP],
                'constraints' => [
                    new Assert\NotBlank([
                        'groups' => [Base::VALIDATION_GROUP],
                    ]),
                ],
            ])
            ->add('rate', MoneyType::class, [
                'label' => Base::TRANS_KEY . '.rate',
                'validation_groups' => [Base::VALIDATION_GROUP],
                'constraints' => [
                    new Assert\NotBlank([
                        'groups' => [Base::VALIDATION_GROUP],
                    ]),
                ],
            ])
        ;
    }
}

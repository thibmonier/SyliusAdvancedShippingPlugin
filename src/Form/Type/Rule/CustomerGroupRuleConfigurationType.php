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

namespace MonsieurBiz\SyliusAdvancedShippingPlugin\Form\Type\Rule;

use MonsieurBiz\SyliusAdvancedShippingPlugin\Checker\Rule\CustomerGroupRuleChecker;
use Sylius\Bundle\CustomerBundle\Form\Type\CustomerGroupCodeChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class CustomerGroupRuleConfigurationType extends AbstractType
{
    public const VALIDATION_GROUP = 'customer_group_checker';

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameters)
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('customerGroup', CustomerGroupCodeChoiceType::class, [
                'label' => 'monsieurbiz_advanced_shipping.form.customer_group_restriction.customer_group',
                'validation_groups' => [self::VALIDATION_GROUP],
                'constraints' => [
                    new Assert\NotBlank([
                        'groups' => [self::VALIDATION_GROUP],
                    ]),
                ],
            ])
        ;
    }

    public function getBlockPrefix()
    {
        return 'app_shipping_method_rule_' . CustomerGroupRuleChecker::TYPE;
    }
}

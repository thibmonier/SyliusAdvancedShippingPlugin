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

namespace MonsieurBiz\SyliusAdvancedShippingPlugin\Form\Type\Calculator\CountryCalculatorConfiguration;

use MonsieurBiz\SyliusAdvancedShippingPlugin\Form\Type\Calculator\CountryCalculatorConfigurationType as Base;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Form\Type\ComparisonChoiceType;
use Sylius\Bundle\MoneyBundle\Form\Type\MoneyType;
use Sylius\Component\Addressing\Model\CountryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class RuleType extends AbstractType
{
    public function __construct(private RepositoryInterface $countryRepository)
    {
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $countryCodes = [];
        /** @var CountryInterface $country */
        foreach ($this->countryRepository->findAll() as $country) {
            $countryCodes[$country->getName()] = $country->getCode();
        }

        $builder
            ->add('comparisonType', ComparisonChoiceType::class, [
                'label' => Base::TRANS_KEY . '.comparison_type',
                'validation_groups' => [Base::VALIDATION_GROUP],
                'constraints' => [
                    new Assert\NotBlank([
                        'groups' => [Base::VALIDATION_GROUP],
                    ]),
                ],
            ])
            ->add('countryCodes', ChoiceType::class, [
                'label' => Base::TRANS_KEY . '.country_code',
                'choices' => $countryCodes,
                'multiple' => true,
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

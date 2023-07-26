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

use MonsieurBiz\SyliusAdvancedShippingPlugin\Checker\Rule\ShippingPostcodeRuleChecker;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Form\Type\Calculator\PostcodeCalculatorConfigurationType;
use Sylius\Component\Addressing\Model\CountryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Twig\Environment;

final class ShippingPostcodeRuleConfigurationType extends AbstractType
{
    public const VALIDATION_GROUP = 'postcode_checker';

    private RepositoryInterface $countryRepository;

    private Environment $twig;

    public function __construct(
        RepositoryInterface $countryRepository,
        Environment $twig
    ) {
        $this->countryRepository = $countryRepository;
        $this->twig = $twig;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameters)
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $countryCodes = [];
        /** @var CountryInterface $country */
        foreach ($this->countryRepository->findAll() as $country) {
            $countryCodes[$country->getName()] = $country->getCode();
        }

        $builder
            ->add('countryCode', ChoiceType::class, [
                'label' => PostcodeCalculatorConfigurationType::TRANS_KEY . '.country',
                'choices' => $countryCodes,
                'validation_groups' => [self::VALIDATION_GROUP],
                'constraints' => [
                    new Assert\NotBlank([
                        'groups' => [self::VALIDATION_GROUP],
                    ]),
                ],
            ])
            ->add('comparisonType', ChoiceType::class, [
                'label' => PostcodeCalculatorConfigurationType::TRANS_KEY . '.comparison_type',
                'choices' => [
                    PostcodeCalculatorConfigurationType::TRANS_KEY . '.included' => ShippingPostcodeRuleChecker::$comparisonTypeIncluded,
                    PostcodeCalculatorConfigurationType::TRANS_KEY . '.excluded' => ShippingPostcodeRuleChecker::$comparisonTypeExcluded,
                ],
                'validation_groups' => [self::VALIDATION_GROUP],
                'constraints' => [
                    new Assert\NotBlank([
                        'groups' => [self::VALIDATION_GROUP],
                    ]),
                ],
            ])
            ->add('rule', TextareaType::class, [
                'label' => PostcodeCalculatorConfigurationType::TRANS_KEY . '.rule',
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
        return 'app_shipping_method_rule_' . ShippingPostcodeRuleChecker::TYPE;
    }
}

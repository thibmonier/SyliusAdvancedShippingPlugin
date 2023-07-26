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

use MonsieurBiz\SyliusAdvancedShippingPlugin\Form\Type\Calculator\PostcodeCalculatorConfiguration\RuleType;
use Sylius\Bundle\MoneyBundle\Form\Type\MoneyType;
use Sylius\Component\Addressing\Model\CountryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Twig\Environment;

final class PostcodeCalculatorConfigurationType extends AbstractType
{
    public const TRANS_KEY = 'monsieurbiz_advanced_shipping.form.postcode_calculator_configuration';

    public const VALIDATION_GROUP = 'postcode_calculator';

    private const LABEL_TEMPLATE = '@MonsieurBizSyliusAdvancedShippingPlugin/Admin/Calculator/PostcodeCalculator/_rulesLabel.html.twig';

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
            ->add('defaultRate', MoneyType::class, [
                'label' => self::TRANS_KEY . '.default_rate',
                'validation_groups' => [self::VALIDATION_GROUP],
                'constraints' => [
                    new Assert\NotBlank([
                        'groups' => [self::VALIDATION_GROUP],
                    ]),
                ],
            ])
            ->add('countryCode', ChoiceType::class, [
                'label' => self::TRANS_KEY . '.country',
                'choices' => $countryCodes,
                'validation_groups' => [self::VALIDATION_GROUP],
                'constraints' => [
                    new Assert\NotBlank([
                        'groups' => [self::VALIDATION_GROUP],
                    ]),
                ],
            ])
            ->add('rules', CollectionType::class, [
                'label' => $this->twig->render(self::LABEL_TEMPLATE, ['label' => self::TRANS_KEY . '.rules']),
                'label_html' => true,
                'entry_type' => RuleType::class,
                'entry_options' => ['validation_groups' => self::VALIDATION_GROUP],
                'allow_add' => true,
                'allow_delete' => true,
                'delete_empty' => true,
                'error_bubbling' => false,
                'validation_groups' => [self::VALIDATION_GROUP],
                'constraints' => [
                    new Assert\NotBlank([
                        'groups' => [self::VALIDATION_GROUP],
                    ]),
                    new Assert\Valid([
                        'groups' => [self::VALIDATION_GROUP],
                    ]),
                ],
                'required' => false,
            ])
        ;
    }
}

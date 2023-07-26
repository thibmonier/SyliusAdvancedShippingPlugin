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

namespace MonsieurBiz\SyliusAdvancedShippingPlugin\Fixture\Factory;

use MonsieurBiz\SyliusAdvancedShippingPlugin\Entity\ShippingTypeInterface;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Entity\ShippingTypeTranslationInterface;
use Sylius\Bundle\CoreBundle\Fixture\Factory\AbstractExampleFactory;
use Sylius\Component\Core\Model\ShippingMethodInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShippingTypeExampleFactory extends AbstractExampleFactory
{
    private OptionsResolver $optionsResolver;

    public function __construct(
        private FactoryInterface $shippingTypeFactory,
        private FactoryInterface $shippingTypeTranslationFactory,
        private RepositoryInterface $shippingMethodRepository
    ) {
        $this->optionsResolver = new OptionsResolver();
        $this->configureOptions($this->optionsResolver);
    }

    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefault('code', '')
            ->setAllowedTypes('code', 'string')
            ->setDefault('translations', [])
            ->setAllowedTypes('translations', ['array'])
            ->setDefault('shipping_methods', [])
            ->setAllowedTypes('shipping_methods', ['array'])
        ;
    }

    public function create(array $options = []): ShippingTypeInterface
    {
        $options = $this->optionsResolver->resolve($options);

        /** @var ShippingTypeInterface $shippingType */
        $shippingType = $this->shippingTypeFactory->createNew();
        $shippingType->setCode($options['code']);
        $this->createTranslations($shippingType, $options);
        $this->associateToShippingMethod($shippingType, $options);

        return $shippingType;
    }

    private function createTranslations(ShippingTypeInterface $ingredient, array $options): void
    {
        foreach ($options['translations'] as $localeCode => $translation) {
            /** @var ShippingTypeTranslationInterface $shippingTypeTranslation */
            $shippingTypeTranslation = $this->shippingTypeTranslationFactory->createNew();
            $shippingTypeTranslation->setLocale($localeCode);
            $shippingTypeTranslation->setLabel($translation['label']);
            $shippingTypeTranslation->setDescription($translation['description']);

            $ingredient->addTranslation($shippingTypeTranslation);
        }
    }

    private function associateToShippingMethod(ShippingTypeInterface $shippingType, array $options): void
    {
        $shippingMethods = $this->shippingMethodRepository->findBy(['code' => $options['shipping_methods']]);

        /** @var ShippingMethodInterface $shippingMethod */
        foreach ($shippingMethods as $shippingMethod) {
            $shippingType->addMethod($shippingMethod);
        }
    }
}

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

namespace MonsieurBiz\SyliusAdvancedShippingPlugin\Fixture;

use Doctrine\ORM\EntityManagerInterface;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Calculator\ChainedCalculator;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Entity\AddressProviderAwareInterface;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Entity\MapProviderConfigurationInterface;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Entity\MapProviderConfigurationTranslationInterface;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Entity\ShippingAddressProviderConfigurationInterface;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Entity\ShippingAddressProviderConfigurationTranslationInterface;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Entity\ShippingCalculatorConfigurationInterface;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Entity\ShippingCalculatorConfigurationTranslationInterface;
use Sylius\Bundle\FixturesBundle\Fixture\AbstractFixture;
use Sylius\Bundle\FixturesBundle\Fixture\FixtureInterface;
use Sylius\Component\Core\Model\ShippingMethodInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\Shipping\Model\ShippingMethodRuleInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

final class AdvancedShippingFixture extends AbstractFixture implements FixtureInterface
{
    private PropertyAccessor $accessor;

    /**
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        private RepositoryInterface $shippingMethodRepository,
        private EntityManagerInterface $shippingMethodManager,
        private EntityManagerInterface $shippingCalculatorConfigurationManager,
        private EntityManagerInterface $shippingAddressProviderConfigurationManager,
        private EntityManagerInterface $shippingMethodRuleManager,
        private FactoryInterface $shippingCalculatorConfigurationFactory,
        private FactoryInterface $shippingAddressProviderConfigurationFactory,
        private FactoryInterface $shippingCalculatorConfigurationTranslationFactory,
        private FactoryInterface $shippingAddressProviderConfigurationTranslationFactory,
        private FactoryInterface $shippingMethodRuleFactory,
        private FactoryInterface $mapProviderConfigurationFactory,
        private FactoryInterface $mapProviderConfigurationTranslationFactory,
        private EntityManagerInterface $mapProviderConfigurationManager,
    ) {
        $this->accessor = PropertyAccess::createPropertyAccessor();
    }

    public function getName(): string
    {
        return 'monsieurbiz_advanced_shipping';
    }

    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function load(array $options): void
    {
        $associations = [];
        foreach ($options['shipping_calculator_configuration'] as $data) {
            /** @var ShippingCalculatorConfigurationInterface $calculatorConfiguration */
            $calculatorConfiguration = $this->shippingCalculatorConfigurationFactory->createNew();
            $associations = $this->prepareCalculatorConfiguration($calculatorConfiguration, $data, $associations);
        }

        foreach ($options['shipping_address_provider_configuration'] as $data) {
            /** @var ShippingAddressProviderConfigurationInterface $providerConfiguration */
            $providerConfiguration = $this->shippingAddressProviderConfigurationFactory->createNew();
            $associations = $this->prepareAddressProviderConfiguration($providerConfiguration, $data, $associations);
        }

        foreach ($options['shipping_method_rule'] as $data) {
            /** @var ShippingMethodRuleInterface $shippingMethodRule */
            $shippingMethodRule = $this->shippingMethodRuleFactory->createNew();
            $associations = $this->prepareShippingMethodRule($shippingMethodRule, $data, $associations);
        }

        foreach ($options['map_provider_configuration'] as $data) {
            /** @var MapProviderConfigurationInterface $providerConfiguration */
            $providerConfiguration = $this->mapProviderConfigurationFactory->createNew();
            $this->prepareMapProviderConfiguration($providerConfiguration, $data);
        }

        $this->shippingCalculatorConfigurationManager->flush();
        $this->shippingAddressProviderConfigurationManager->flush();
        $this->mapProviderConfigurationManager->flush();

        $this->associateToShippingMethods($associations);
    }

    private function addCalculatorTranslations(ShippingCalculatorConfigurationInterface $calculatorConfiguration, array $translations): void
    {
        foreach ($translations as $locale => $infos) {
            /** @var ShippingCalculatorConfigurationTranslationInterface $translation */
            $translation = $this->shippingCalculatorConfigurationTranslationFactory->createNew();
            $translation->setLocale($locale);
            $translation->setname($infos['name']);
            $calculatorConfiguration->addTranslation($translation);
        }
    }

    private function addAddressProviderTranslations(ShippingAddressProviderConfigurationInterface $providerConfiguration, array $translations): void
    {
        foreach ($translations as $locale => $infos) {
            /** @var ShippingAddressProviderConfigurationTranslationInterface $translation */
            $translation = $this->shippingAddressProviderConfigurationTranslationFactory->createNew();
            $translation->setLocale($locale);
            $translation->setname($infos['name']);
            $providerConfiguration->addTranslation($translation);
        }
    }

    private function addMapProviderTranslations(MapProviderConfigurationInterface $providerConfiguration, array $translations): void
    {
        foreach ($translations as $locale => $infos) {
            /** @var MapProviderConfigurationTranslationInterface $translation */
            $translation = $this->mapProviderConfigurationTranslationFactory->createNew();
            $translation->setLocale($locale);
            $translation->setname($infos['name']);
            $providerConfiguration->addTranslation($translation);
        }
    }

    protected function configureOptionsNode(ArrayNodeDefinition $optionsNode): void
    {
        /** @phpstan-ignore-next-line */
        $optionsNode
            ->children()
                ->arrayNode('shipping_calculator_configuration')
                    ->arrayPrototype()
                    ->children()
                        ->scalarNode('calculator')->cannotBeEmpty()->end()
                        ->scalarNode('code')->cannotBeEmpty()->end()
                        ->arrayNode('translations')
                            ->arrayPrototype()
                                ->children()
                                    ->scalarNode('name')->cannotBeEmpty()->end()
                                ->end()
                            ->end()
                        ->end()
                        ->variableNode('configuration')->end()
                        ->arrayNode('shipping_methods')->scalarPrototype()->end()->end()
                    ->end()
                ->end()
            ->end()
                ->arrayNode('shipping_address_provider_configuration')
                    ->arrayPrototype()
                    ->children()
                        ->scalarNode('provider')->cannotBeEmpty()->end()
                        ->scalarNode('code')->cannotBeEmpty()->end()
                        ->arrayNode('translations')
                            ->arrayPrototype()
                                ->children()
                                    ->scalarNode('name')->cannotBeEmpty()->end()
                                ->end()
                            ->end()
                        ->end()
                        ->variableNode('configuration')->end()
                        ->arrayNode('shipping_methods')->scalarPrototype()->end()->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('shipping_method_rule')
                    ->arrayPrototype()
                    ->children()
                        ->scalarNode('rule')->cannotBeEmpty()->end()
                        ->variableNode('configuration')->end()
                        ->arrayNode('shipping_methods')->scalarPrototype()->end()->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('map_provider_configuration')
                    ->arrayPrototype()
                    ->children()
                        ->scalarNode('provider')->cannotBeEmpty()->end()
                        ->scalarNode('code')->cannotBeEmpty()->end()
                        ->arrayNode('translations')
                            ->arrayPrototype()
                                ->children()
                                    ->scalarNode('name')->cannotBeEmpty()->end()
                                ->end()
                            ->end()
                        ->end()
                        ->variableNode('configuration')->end()
                        ->arrayNode('shipping_methods')->scalarPrototype()->end()->end()
                        ->end()
                    ->end()
                ->end()
        ;
    }

    private function associateToShippingMethods(array $associations): void
    {
        foreach ($associations as $methodCode => $configurations) {
            /** @var ShippingMethodInterface|null $method */
            $method = $this->shippingMethodRepository->findOneBy(['code' => $methodCode]);
            if (null === $method) {
                continue;
            }

            $this->associateWithCalculators($method, $configurations['calculators'] ?? []);
            $this->associateWithProviders($method, $configurations['shipping_providers'] ?? []);
            $this->associateWithRules($method, $configurations['shipping_method_rules'] ?? []);

            $this->shippingMethodManager->persist($method);
        }

        $this->shippingMethodManager->flush();
    }

    private function addChainedConfiguration(ShippingMethodInterface $method, array $calculatorConfigurations): void
    {
        $currentConfiguration = $method->getConfiguration() ?? [];
        foreach ($calculatorConfigurations as $calculatorConfiguration) {
            $currentConfiguration['calculators'][$calculatorConfiguration->getId()] = $calculatorConfiguration->getId();
        }
        $method->setConfiguration($currentConfiguration);
    }

    private function prepareCalculatorConfiguration(ShippingCalculatorConfigurationInterface $calculatorConfiguration, array $data, array $associations): array
    {
        $calculatorConfiguration->setCalculator($data['calculator']);
        $calculatorConfiguration->setCode($data['code']);
        $calculatorConfiguration->setConfiguration((array) $this->prepareConfiguration($data['configuration']));
        $this->addCalculatorTranslations($calculatorConfiguration, $data['translations']);

        $this->shippingCalculatorConfigurationManager->persist($calculatorConfiguration);

        if (true === isset($data['shipping_methods']) && true === \is_array($data['shipping_methods'])) {
            foreach ($data['shipping_methods'] as $methodCode) {
                $associations[$methodCode]['calculators'][] = $calculatorConfiguration;
            }
        }

        return $associations;
    }

    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    private function prepareConfiguration(array $configuration): array|object
    {
        if (true === isset($configuration['object'])) {
            $object = new $configuration['object']['class']();
            foreach ($configuration['object']['params'] as $propertyPath => $propertyValue) {
                $this->accessor->setValue($object, $propertyPath, $propertyValue);
            }

            return $object;
        }

        $prepared = [];
        foreach ($configuration as $param => $value) {
            if (true === \is_array($value)) {
                $prepared[$param] = $this->prepareConfiguration($value);

                continue;
            }

            $prepared[$param] = $value;
        }

        return $prepared;
    }

    private function prepareAddressProviderConfiguration(ShippingAddressProviderConfigurationInterface $addressProviderConfiguration, array $data, array $associations): array
    {
        $addressProviderConfiguration->setProvider($data['provider']);
        $addressProviderConfiguration->setCode($data['code']);
        $addressProviderConfiguration->setConfiguration((array) $this->prepareConfiguration($data['configuration']));
        $this->addAddressProviderTranslations($addressProviderConfiguration, $data['translations']);

        $this->shippingAddressProviderConfigurationManager->persist($addressProviderConfiguration);

        if (true === isset($data['shipping_methods']) && true === \is_array($data['shipping_methods'])) {
            foreach ($data['shipping_methods'] as $methodCode) {
                $associations[$methodCode]['shipping_providers'][] = $addressProviderConfiguration;
            }
        }

        return $associations;
    }

    private function prepareMapProviderConfiguration(MapProviderConfigurationInterface $mapProviderConfiguration, array $data): void
    {
        $mapProviderConfiguration->setProvider($data['provider']);
        $mapProviderConfiguration->setCode($data['code']);
        $mapProviderConfiguration->setConfiguration((array) $this->prepareConfiguration($data['configuration']));
        $this->addMapProviderTranslations($mapProviderConfiguration, $data['translations']);

        $this->mapProviderConfigurationManager->persist($mapProviderConfiguration);
    }

    private function prepareShippingMethodRule(ShippingMethodRuleInterface $shippingMethodRule, array $data, array $associations): array
    {
        $shippingMethodRule->setType($data['rule']);
        $shippingMethodRule->setConfiguration($data['configuration']);

        if (true === isset($data['shipping_methods']) && true === \is_array($data['shipping_methods'])) {
            foreach ($data['shipping_methods'] as $methodCode) {
                $associations[$methodCode]['shipping_method_rules'][] = clone $shippingMethodRule;
            }
        }

        return $associations;
    }

    private function associateWithCalculators(ShippingMethodInterface $method, array $calculators): void
    {
        if (ChainedCalculator::TYPE === $method->getCalculator()) {
            $this->addChainedConfiguration($method, $calculators);
        }
    }

    private function associateWithProviders(ShippingMethodInterface $method, array $providers): void
    {
        foreach ($providers as $shippingAddressProviderConfiguration) {
            if ($method instanceof AddressProviderAwareInterface) {
                $method->setShippingAddressProviderConfiguration($shippingAddressProviderConfiguration);
            }
        }
    }

    private function associateWithRules(ShippingMethodInterface $method, array $rules): void
    {
        foreach ($rules as $shippingMethodRule) {
            $method->addRule($shippingMethodRule);
        }
    }
}

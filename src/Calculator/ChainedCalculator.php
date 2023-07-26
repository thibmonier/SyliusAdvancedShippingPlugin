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

namespace MonsieurBiz\SyliusAdvancedShippingPlugin\Calculator;

use MonsieurBiz\SyliusAdvancedShippingPlugin\Entity\ShippingCalculatorConfigurationInterface;
use Sylius\Component\Registry\ServiceRegistryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\Shipping\Calculator\CalculatorInterface;
use Sylius\Component\Shipping\Model\ShipmentInterface;

final class ChainedCalculator implements CalculatorInterface
{
    public const TYPE = 'chained';

    private ServiceRegistryInterface $calculators;

    private RepositoryInterface $calculatorConfigRepository;

    public function __construct(
        ServiceRegistryInterface $calculators,
        RepositoryInterface $calculatorConfigRepository
    ) {
        $this->calculators = $calculators;
        $this->calculatorConfigRepository = $calculatorConfigRepository;
    }

    public function calculate(ShipmentInterface $subject, array $configuration): int
    {
        $total = 0;

        $calculators = $this->getCalculators($configuration);
        /** @var ShippingCalculatorConfigurationInterface $calculatorConfiguration */
        foreach ($calculators as $calculatorConfiguration) {
            // Append current total in configuration to use it in calculators
            $configuration = array_merge($calculatorConfiguration->getConfiguration(), ['current_total' => $total]);
            /** @var CalculatorInterface $calculator */
            $calculator = $this->calculators->get($calculatorConfiguration->getCalculator());
            $total += $calculator->calculate($subject, $configuration);
        }

        return $total;
    }

    /**
     * Retrieve calculators on the same order as the shipping configuration.
     */
    private function getCalculators(array $configuration): array
    {
        $calculators = [];
        $calculatorConfigurationCollection = $this->calculatorConfigRepository->findBy(['id' => $configuration['calculators'] ?? null]);
        /** @var ShippingCalculatorConfigurationInterface $calculatorConfiguration */
        foreach ($calculatorConfigurationCollection as $calculatorConfiguration) {
            $calculators[$calculatorConfiguration->getId()] = $calculatorConfiguration;
        }

        $orderedCalculators = [];
        foreach ($configuration['calculators'] as $calculatorId) {
            if (isset($calculators[$calculatorId])) {
                $orderedCalculators[$calculatorId] = $calculators[$calculatorId];
            }
        }

        return $orderedCalculators;
    }

    public function getType(): string
    {
        return self::TYPE;
    }
}

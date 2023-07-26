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

use Sylius\Component\Shipping\Calculator\CalculatorInterface;
use Sylius\Component\Shipping\Model\ShipmentInterface;

final class WeightBasedTableRateCalculator extends AbstractTableRateCalculator implements CalculatorInterface
{
    public const TYPE = 'weight_based_table_rate';

    public function calculate(ShipmentInterface $subject, array $configuration): int
    {
        $rateTable = $this->sortRateTable($configuration['rateTable']);
        foreach ($rateTable as $level) {
            if ($subject->getShippingWeight() <= $level['limit']) {
                return $level['rate'];
            }
        }

        return $configuration['defaultRate'];
    }

    public function getType(): string
    {
        return self::TYPE;
    }
}

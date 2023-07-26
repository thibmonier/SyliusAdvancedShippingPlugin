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

abstract class AbstractTableRateCalculator implements CalculatorInterface
{
    protected function sortRateTable(array $rateTable): array
    {
        usort($rateTable, function (array $value, array $compare): int {
            return $value['limit'] <=> $compare['limit'];
        });

        return $rateTable;
    }
}

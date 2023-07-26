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

use Sylius\Bundle\ShopBundle\Calculator\OrderItemsSubtotalCalculatorInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\ShipmentInterface as CoreShipmentInterface;
use Sylius\Component\Shipping\Calculator\CalculatorInterface;
use Sylius\Component\Shipping\Model\ShipmentInterface;

final class TotalBasedTableRateCalculator extends AbstractTableRateCalculator implements CalculatorInterface
{
    public const TYPE = 'total_based_table_rate';

    private OrderItemsSubtotalCalculatorInterface $calculator;

    public function __construct(OrderItemsSubtotalCalculatorInterface $calculator)
    {
        $this->calculator = $calculator;
    }

    public function calculate(ShipmentInterface $subject, array $configuration): int
    {
        /** @var CoreShipmentInterface $subject */
        $rateTable = $this->sortRateTable($configuration['rateTable']);
        /** @var OrderInterface|null $order */
        $order = $subject->getOrder();

        if (null === $order) {
            return $configuration['defaultRate'];
        }

        // Add promotion in subtotal (The amount is negative)
        $subtotalWithPromo = $this->calculator->getSubtotal($order) + $order->getOrderPromotionTotal();
        foreach ($rateTable as $level) {
            if ($subtotalWithPromo < $level['limit']) {
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

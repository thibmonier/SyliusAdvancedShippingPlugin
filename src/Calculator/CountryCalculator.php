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

use MonsieurBiz\SyliusAdvancedShippingPlugin\Checker\AddressBasedCheckerTrait;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Checker\RulesBasedCheckerTrait;
use Sylius\Component\Shipping\Calculator\CalculatorInterface;
use Sylius\Component\Shipping\Model\ShipmentInterface;

final class CountryCalculator extends AbstractTableRateCalculator implements CalculatorInterface
{
    use AddressBasedCheckerTrait;
    use RulesBasedCheckerTrait;

    public const TYPE = 'country';

    public function calculate(ShipmentInterface $subject, array $configuration): int
    {
        /** @var \Sylius\Component\Core\Model\ShipmentInterface $subject */
        $countryCode = $this->getShippingAddress($subject)?->getCountryCode();
        if (null === $countryCode) {
            return $configuration['defaultRate'];
        }

        $eligibleRule = $this->getEligibleRule(
            $countryCode,
            $configuration['rules'],
            fn (string $countryCode, array $rule): bool => \in_array($countryCode, $rule['countryCodes'] ?? [], true),
        );

        return $eligibleRule['rate'] ?? $configuration['defaultRate'];
    }

    public function getType(): string
    {
        return self::TYPE;
    }
}

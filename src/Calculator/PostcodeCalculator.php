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

use MonsieurBiz\SyliusAdvancedShippingPlugin\Checker\PostcodeCheckerTrait;
use Sylius\Component\Core\Model\ShipmentInterface as CoreShipmentInterface;
use Sylius\Component\Shipping\Calculator\CalculatorInterface;
use Sylius\Component\Shipping\Model\ShipmentInterface;

final class PostcodeCalculator implements CalculatorInterface
{
    use PostcodeCheckerTrait;

    public const TYPE = 'postcode';

    public function calculate(ShipmentInterface $subject, array $configuration): int
    {
        /** @var CoreShipmentInterface $subject */
        $postcode = $this->getShippingPostcode($subject, $configuration['countryCode']);
        if (null === $postcode) {
            return $configuration['defaultRate'];
        }

        return $this->getPostcodeRate(
            $postcode,
            $configuration['rules'],
            $configuration['defaultRate']
        );
    }

    private function getPostcodeRate(string $postcode, array $rules, int $defaultRate): int
    {
        $eligibleRule = $this->getEligibleRule($postcode, $rules);

        return false !== $eligibleRule ? $eligibleRule['rate'] : $defaultRate;
    }

    public function getType(): string
    {
        return self::TYPE;
    }
}

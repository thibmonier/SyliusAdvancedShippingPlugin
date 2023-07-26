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

namespace MonsieurBiz\SyliusAdvancedShippingPlugin\Checker;

use Sylius\Component\Core\Model\ShipmentInterface as CoreShipmentInterface;

trait PostcodeCheckerTrait
{
    use AddressBasedCheckerTrait;

    public static string $comparisonTypeIncluded = 'included';

    public static string $comparisonTypeExcluded = 'excluded';

    protected function getShippingPostcode(CoreShipmentInterface $subject, string $countryCode): ?string
    {
        $shippingAddress = $this->getShippingAddress($subject);
        if (null === $shippingAddress) {
            return null;
        }

        if ($countryCode !== $shippingAddress->getCountryCode() || null === $shippingAddress->getPostcode()) {
            return null;
        }

        return $shippingAddress->getPostcode();
    }

    /**
     * @return array|false
     */
    protected function getEligibleRule(string $postcode, array $rules)
    {
        foreach ($rules as $rule) {
            $isInRule = $this->isPostcodeFindedInRule($rule['rule'], $postcode);
            $comparisonType = $rule['comparisonType'];
            if ($this->isIncluded($comparisonType, $isInRule) || $this->isExcluded($comparisonType, $isInRule)) {
                return $rule;
            }
        }

        return false;
    }

    protected function isIncluded(string $comparisonType, bool $isInRule): bool
    {
        return self::$comparisonTypeIncluded === $comparisonType && true === $isInRule;
    }

    protected function isExcluded(string $comparisonType, bool $isInRule): bool
    {
        return self::$comparisonTypeExcluded === $comparisonType && false === $isInRule;
    }

    protected function isPostcodeFindedInRule(string $rule, string $postcode): bool
    {
        $conditions = explode(',', $rule);
        foreach ($conditions as $expr) {
            $expr = sprintf('/^%s$/', str_replace('*', '[0-9a-zA-Z]+', trim($expr)));
            $found = (bool) preg_match($expr, $postcode);
            if (true === $found) {
                return true;
            }
        }

        return false;
    }
}

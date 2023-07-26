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

trait RulesBasedCheckerTrait
{
    protected function getEligibleRule(mixed $value, array $rules, callable $exists): ?array
    {
        foreach ($rules as $rule) {
            $isInRule = $exists($value, $rule);
            $comparisonType = $rule['comparisonType'];
            if ($this->isIncluded($comparisonType, $isInRule) || $this->isExcluded($comparisonType, $isInRule)) {
                return $rule;
            }
        }

        return null;
    }

    protected function isIncluded(string $comparisonType, bool $isInRule): bool
    {
        return ComparisonType::INCLUDED === $comparisonType && true === $isInRule;
    }

    protected function isExcluded(string $comparisonType, bool $isInRule): bool
    {
        return ComparisonType::EXCLUDED === $comparisonType && false === $isInRule;
    }
}

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

namespace MonsieurBiz\SyliusAdvancedShippingPlugin\Checker\Rule;

use MonsieurBiz\SyliusAdvancedShippingPlugin\Checker\PostcodeCheckerTrait;
use Sylius\Component\Core\Model\ShipmentInterface;
use Sylius\Component\Shipping\Checker\Rule\RuleCheckerInterface;
use Sylius\Component\Shipping\Model\ShippingSubjectInterface;

final class ShippingPostcodeRuleChecker implements RuleCheckerInterface
{
    use PostcodeCheckerTrait;

    public const TYPE = 'shipping_address_postcode_restriction';

    public function isEligible(ShippingSubjectInterface $subject, array $configuration): bool
    {
        if (!$subject instanceof ShipmentInterface) {
            return false;
        }

        $shippingAddress = $this->getShippingAddress($subject);
        if (null === $shippingAddress) {
            return false;
        }

        if ($shippingAddress->getCountryCode() !== $configuration['countryCode']) {
            return true;
        }
        /** @var string $postcode */
        $postcode = $shippingAddress->getPostcode();

        // in the shipping method rule, the configuration contains only one rule.
        return $this->isPostcodeEligible($postcode, [$configuration]);
    }

    private function isPostcodeEligible(string $postcode, array $rules): bool
    {
        return false !== $this->getEligibleRule($postcode, $rules);
    }
}

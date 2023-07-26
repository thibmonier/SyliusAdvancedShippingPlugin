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
use Sylius\Component\Customer\Model\CustomerGroupInterface;
use Sylius\Component\Shipping\Checker\Rule\RuleCheckerInterface;
use Sylius\Component\Shipping\Model\ShippingSubjectInterface;

final class CustomerGroupRuleChecker implements RuleCheckerInterface
{
    use PostcodeCheckerTrait;

    public const TYPE = 'customer_group_restriction';

    public function isEligible(ShippingSubjectInterface $subject, array $configuration): bool
    {
        if (!$subject instanceof ShipmentInterface) {
            return false;
        }

        $customerGroup = $this->getCustomerGroup($subject);

        return null !== $customerGroup && $customerGroup->getCode() === $configuration['customerGroup'];
    }

    public function getCustomerGroup(ShipmentInterface $shipment): ?CustomerGroupInterface
    {
        if (null === $shipment->getOrder() || null === $shipment->getOrder()->getCustomer()) {
            return null;
        }

        return $shipment->getOrder()->getCustomer()->getGroup();
    }
}

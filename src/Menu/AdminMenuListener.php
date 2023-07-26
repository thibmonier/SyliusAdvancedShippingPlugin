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

namespace MonsieurBiz\SyliusAdvancedShippingPlugin\Menu;

use Knp\Menu\ItemInterface;
use Knp\Menu\Util\MenuManipulator;
use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

final class AdminMenuListener
{
    private MenuManipulator $manipulator;

    public function __construct(MenuManipulator $manipulator)
    {
        $this->manipulator = $manipulator;
    }

    public function addAdminMenuItem(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();
        $content = $menu->getChild('monsieurbiz-advanced-shipping');

        if (null === $content) {
            $content = $menu
                ->addChild('monsieurbiz-advanced-shipping')
                ->setLabel('monsieurbiz_advanced_shipping.menu.shipping')
            ;
        }

        $this->moveConfigurationShippingEntries($menu, $content);

        $content->addChild(
            'monsieurbiz-advanced-shipping-calculator',
            ['route' => 'monsieurbiz_advanced_shipping_admin_shipping_calculator_configuration_index']
        )
            ->setLabel('monsieurbiz_advanced_shipping.menu.calculators')
            ->setLabelAttribute('icon', 'money')
        ;

        $content->addChild(
            'monsieurbiz-advanced-shipping-address-provider',
            ['route' => 'monsieurbiz_advanced_shipping_admin_shipping_address_provider_configuration_index']
        )
            ->setLabel('monsieurbiz_advanced_shipping.menu.address_providers')
            ->setLabelAttribute('icon', 'address card')
        ;

        $content->addChild(
            'monsieurbiz-advanced-shipping-type',
            ['route' => 'monsieurbiz_advanced_shipping_admin_shipping_type_index']
        )
            ->setLabel('monsieurbiz_advanced_shipping.menu.shipping_type')
            ->setLabelAttribute('icon', 'map')
        ;

        $content->addChild(
            'monsieurbiz-advanced-map-provider',
            ['route' => 'monsieurbiz_advanced_shipping_admin_map_provider_configuration_index']
        )
            ->setLabel('monsieurbiz_advanced_shipping.menu.map_providers')
            ->setLabelAttribute('icon', 'map marker alternate')
        ;

        $this->manipulator->moveToPosition($content, 4);
    }

    private function moveConfigurationShippingEntries(ItemInterface $menu, ItemInterface $content): void
    {
        $configuration = $menu->getChild('configuration');
        if (null !== $configuration) {
            $methods = $configuration->getChild('shipping_methods');
            $categories = $configuration->getChild('shipping_categories');
            if (null !== $methods) {
                $configuration->removeChild('shipping_methods');
                $content->addChild($methods);
            }
            if (null !== $categories) {
                $configuration->removeChild('shipping_categories');
                $content->addChild($categories);
            }
        }
    }
}

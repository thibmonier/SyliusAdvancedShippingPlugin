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

namespace MonsieurBiz\SyliusAdvancedShippingPlugin\Fixture;

use Doctrine\ORM\EntityManagerInterface;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Fixture\Factory\ShippingTypeExampleFactory;
use Sylius\Bundle\CoreBundle\Fixture\AbstractResourceFixture;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

class ShippingTypeFixture extends AbstractResourceFixture
{
    public function __construct(
        EntityManagerInterface $shippingTypeManager,
        ShippingTypeExampleFactory $exampleFactory
    ) {
        parent::__construct($shippingTypeManager, $exampleFactory);
    }

    public function getName(): string
    {
        return 'monsieurbiz_shipping_type';
    }

    protected function configureResourceNode(ArrayNodeDefinition $resourceNode): void
    {
        /** @phpstan-ignore-next-line */
        $resourceNode
            ->children()
                ->scalarNode('code')->cannotBeEmpty()->end()
                ->arrayNode('translations')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('label')->cannotBeEmpty()->end()
                            ->scalarNode('description')->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('shipping_methods')
                    ->scalarPrototype()->end()
                ->end()
            ->end()
        ;
    }
}

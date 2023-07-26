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

namespace MonsieurBiz\SyliusAdvancedShippingPlugin\Twig\Extension;

use MonsieurBiz\SyliusAdvancedShippingPlugin\Entity\MapProviderConfigurationInterface;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Factory\MapProviderFactoryInterface;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Provider\MapProvider\MapProviderInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

final class MapProviderConfigurationExtension extends AbstractExtension
{
    public function __construct(
        private RepositoryInterface $mapProviderConfigurationRepository,
        private MapProviderFactoryInterface $mapProviderFactory,
    ) {
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('load_map_provider', [$this, 'loadMapProvider']),
        ];
    }

    public function loadMapProvider(string $code): ?MapProviderInterface
    {
        /** @var MapProviderConfigurationInterface|null $configuration */
        $configuration = $this->mapProviderConfigurationRepository->findOneBy(['code' => $code]);
        if (null === $configuration) {
            return null;
        }

        return $this->mapProviderFactory->createFromConfiguration($configuration);
    }
}

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

namespace MonsieurBiz\SyliusAdvancedShippingPlugin;

use MonsieurBiz\SyliusAdvancedShippingPlugin\DependencyInjection\Compiler\RegisterAddressAutocompleteProviderPass;
use MonsieurBiz\SyliusAdvancedShippingPlugin\DependencyInjection\Compiler\RegisterMapProviderPass;
use MonsieurBiz\SyliusAdvancedShippingPlugin\DependencyInjection\Compiler\RegisterShippingAddressProviderPass;
use Sylius\Bundle\CoreBundle\Application\SyliusPluginTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class MonsieurBizSyliusAdvancedShippingPlugin extends Bundle
{
    use SyliusPluginTrait;

    public function getContainerExtension(): ?ExtensionInterface
    {
        if (null === $this->containerExtension) {
            $this->containerExtension = false;
            $extension = $this->createContainerExtension();
            if (null !== $extension) {
                $this->containerExtension = $extension;
            }
        }

        return $this->containerExtension instanceof ExtensionInterface
            ? $this->containerExtension
            : null;
    }

    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new RegisterShippingAddressProviderPass());
        $container->addCompilerPass(new RegisterMapProviderPass());
        $container->addCompilerPass(new RegisterAddressAutocompleteProviderPass());
    }
}

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

namespace MonsieurBiz\SyliusAdvancedShippingPlugin\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

abstract class AbstractRegisterPass
{
    protected function register(ContainerBuilder $container, string $registryId, string $formRegistryId, string $tag, string $parameter): void
    {
        if (false === $this->hasDefinitions($container, $registryId, $formRegistryId)) {
            return;
        }

        $registry = $container->getDefinition($registryId);
        $formTypeRegistry = $container->getDefinition($formRegistryId);
        $taggedServices = [];

        foreach ($container->findTaggedServiceIds($tag) as $id => $definitions) {
            foreach ($definitions as $configuration) {
                $this->validate($tag, $configuration);
                $code = $configuration['code'];
                $taggedServices[$code] = $configuration['label'];
                $this->registerServices($id, $code, $configuration, $registry, $formTypeRegistry);
            }
        }

        $container->setParameter($parameter, $taggedServices);
    }

    protected function simpleRegister(ContainerBuilder $container, string $registryId, string $tag, string $parameter): void
    {
        if (false === $container->hasDefinition($registryId)) {
            return;
        }

        $registry = $container->getDefinition($registryId);
        $taggedServices = [];

        foreach ($container->findTaggedServiceIds($tag) as $id => $definitions) {
            foreach ($definitions as $configuration) {
                $this->validate($tag, $configuration);
                $code = $configuration['code'];
                $taggedServices[$code] = $configuration['label'];
                $registry->addMethodCall('register', [$code, new Reference($id)]);
            }
        }

        $container->setParameter($parameter, $taggedServices);
    }

    private function validate(string $tag, array $configuration): void
    {
        if (!isset($configuration['code'], $configuration['label'])) {
            throw new \InvalidArgumentException(sprintf('Service tagged with %s needs to have `code` and `label` attributes.', $tag));
        }
    }

    private function registerServices(string $id, string $code, array $configuration, Definition $registry, Definition $formTypeRegistry): void
    {
        $registry->addMethodCall('register', [$code, new Reference($id)]);
        if (isset($configuration['form_type'])) {
            $formTypeRegistry->addMethodCall('add', [$code, 'default', $configuration['form_type']]);
        }
    }

    private function hasDefinitions(ContainerBuilder $container, string $registryId, string $formRegistryId): bool
    {
        return $container->hasDefinition($registryId) || !$container->hasDefinition($formRegistryId);
    }
}

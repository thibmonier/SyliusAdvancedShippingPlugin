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

namespace MonsieurBiz\SyliusAdvancedShippingPlugin\Form\Type\AddressProvider;

use Sylius\Bundle\ResourceBundle\Form\DataTransformer\ResourceToIdentifierTransformer;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\ReversedTransformer;
use Symfony\Component\Validator\Constraints as Assert;

final class PickupPointProviderConfigurationType extends AbstractType
{
    public function __construct(
        private RepositoryInterface $mapProviderConfigurationRepository,
        #[Autowire(param: 'monsieurbiz_advanced_shipping.model.map_provider_configuration.class')]
        private string $mapProviderConfigurationClass,
    ) {
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('mapProviderConfiguration', EntityType::class, [
                'class' => $this->mapProviderConfigurationClass,
                'label' => 'monsieurbiz_advanced_shipping.form.map_provider',
                'constraints' => [
                    new Assert\NotBlank([
                        'groups' => ['pickup_point_address_provider'],
                    ]),
                ],
            ])
        ;

        $builder
            ->get('mapProviderConfiguration')
            ->addModelTransformer(new ReversedTransformer(new ResourceToIdentifierTransformer($this->mapProviderConfigurationRepository, 'code')))
        ;
    }
}

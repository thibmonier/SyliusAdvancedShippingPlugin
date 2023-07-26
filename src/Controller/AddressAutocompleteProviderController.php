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

namespace MonsieurBiz\SyliusAdvancedShippingPlugin\Controller;

use MonsieurBiz\SyliusAdvancedShippingPlugin\Provider\AddressAutocompleteProvider\AddressAutocompleteProviderInterface;
use Sylius\Component\Registry\ServiceRegistryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class AddressAutocompleteProviderController extends AbstractController
{
    public function __construct(private ServiceRegistryInterface $addressAutocompleteProviderRegistry)
    {
    }

    public function searchAddressAction(Request $request, string $provider, string $template): Response
    {
        $query = (string) $request->request->get('query');
        $country = (string) $request->request->get('country');
        $limit = (int) $request->request->get('limit');
        if (
            empty($query)
            || 0 >= $limit
            || false === $this->addressAutocompleteProviderRegistry->has($provider)
        ) {
            throw new NotFoundHttpException();
        }

        $provider = $this->addressAutocompleteProviderRegistry->get($provider);
        /** @var AddressAutocompleteProviderInterface $provider */
        $results = $provider->search($query, $country, $limit);

        return match ($request->getRequestFormat()) {
            'json' => $this->json($results),
            default => $this->render($template, ['results' => $results]),
        };
    }
}

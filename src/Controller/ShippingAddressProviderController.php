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

use App\Entity\Shipping\ShippingMethodInterface;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Helper\ShippingAddressProviderHelperInterface;
use MonsieurBiz\SyliusAdvancedShippingPlugin\Provider\ShippingAddressProvider\ShippingAddressProviderInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class ShippingAddressProviderController extends AbstractController
{
    private array $providers;

    private RepositoryInterface $shippingMethodRepository;

    private ShippingAddressProviderHelperInterface $providerHelper;

    public function __construct(
        array $providers,
        RepositoryInterface $shippingMethodRepository,
        ShippingAddressProviderHelperInterface $providerHelper
    ) {
        $this->providers = $providers;
        $this->shippingMethodRepository = $shippingMethodRepository;
        $this->providerHelper = $providerHelper;
    }

    public function getProviderListAction(string $template): Response
    {
        return $this->render($template, ['providers' => $this->providers]);
    }

    public function pickupPointListAction(Request $request, int $methodId, string $format, string $template): Response
    {
        $method = $this->getMethod($methodId);
        $provider = $this->getProvider($method);
        $metadata = $request->query->all();
        $list = $provider->getAddressListFromMetadata($metadata);

        return $this->getListReponse($list, $format, $template, $method);
    }

    private function getListReponse(array $list, string $format, string $template, ShippingMethodInterface $method): Response
    {
        switch ($format) {
            case 'json':
                return new JsonResponse($list);
            case 'html':
            default:
                return $this->render($template, ['pickupPointList' => $list, 'method' => $method]);
        }
    }

    private function getMethod(int $methodId): ShippingMethodInterface
    {
        if (null === ($method = $this->shippingMethodRepository->find($methodId))) {
            throw new NotFoundHttpException();
        }

        /** @var ShippingMethodInterface $method */
        return $method;
    }

    private function getProvider(ShippingMethodInterface $method): ShippingAddressProviderInterface
    {
        if (null === ($provider = $this->providerHelper->getProviderByMethod($method))) {
            throw new NotFoundHttpException();
        }

        return $provider;
    }
}

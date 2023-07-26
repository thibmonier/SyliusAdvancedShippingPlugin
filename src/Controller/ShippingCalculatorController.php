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

use MonsieurBiz\SyliusAdvancedShippingPlugin\Calculator\ChainedCalculator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

final class ShippingCalculatorController extends AbstractController
{
    private array $calculators;

    public function __construct(array $calculators)
    {
        unset($calculators[ChainedCalculator::TYPE]);
        $this->calculators = $calculators;
    }

    public function getListAction(string $template): Response
    {
        return $this->render($template, ['calculators' => $this->calculators]);
    }
}

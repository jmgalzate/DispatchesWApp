<?php

/**
 * TODO: remove this Service and manage the Sessions from DeliveryController
 */

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;

class ScanService
{

    public function __construct(private readonly RequestStack $requestStack)
    {
    }

    public function scanProduct(string $barcode): void
    {
        $scannedProducts = [$barcode];
        $this->requestStack->getSession()->set('scannedProducts', $scannedProducts);
    }
}
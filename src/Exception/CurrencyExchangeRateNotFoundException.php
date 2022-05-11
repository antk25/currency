<?php

namespace App\Exception;

use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

class CurrencyExchangeRateNotFoundException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Currency Exchange Rate Not Found', Response::HTTP_NOT_FOUND);
    }
}
<?php

namespace App\Exception;

use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

class CurrencyExchangeRateNotDateException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Currency Exchange Rate Date Not Found', Response::HTTP_INTERNAL_SERVER_ERROR);
    }

}
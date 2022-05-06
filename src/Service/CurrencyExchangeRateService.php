<?php

namespace App\Service;

use App\Entity\CurrencyExchangeRate;
use App\Model\CurrencyExchangeRateResponse;
use App\Repository\CurrencyExchangeRateRepository;

class CurrencyExchangeRateService
{
    private CurrencyExchangeRateRepository $currencyExchangeRateRepository;

    public function __construct(CurrencyExchangeRateRepository $currencyExchangeRateRepository)
    {
       $this->currencyExchangeRateRepository = $currencyExchangeRateRepository;
    }

    public function getExchangeRateByDate($base, $second, $date): ?array
    {
        $exchangeRate = $this->currencyExchangeRateRepository->findBy([
            'base' => $base,
            'second' => $second,
            'date' => $date,
        ]);

        if ($exchangeRate)
        {
            $items = array_map(
                fn(CurrencyExchangeRate $currencyExchangeRate) => new CurrencyExchangeRateResponse(
                    $currencyExchangeRate->getBase(),
                    $currencyExchangeRate->getSecond(),
                    $currencyExchangeRate->getDate()->format('Y-m-d'),
                    $currencyExchangeRate->getRate()
                ),
                $exchangeRate
            );

            return $items;
        }
        else
        {
            return null;
        }

    }

    public function getExchangeRateByPeriodDates($base, $second, $datePeriodTo, $datePeriodFrom): array
    {
        return $this->currencyExchangeRateRepository->findAllBetweenDates($base, $second, $datePeriodTo, $datePeriodFrom);
    }
}
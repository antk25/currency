<?php

namespace App\Controller;

use App\Model\CurrencyExchangeRateResponse;
use App\Repository\CurrencyExchangeRateRepository;
use App\Service\CurrencyExchangeRateService;
use App\Service\ExchangeRates\ExchangeRatesService;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class DefaultController extends AbstractController
{
    private CurrencyExchangeRateService $currencyExchangeRateService;
    private ExchangeRatesService $exchangeRatesService;
    public string $base;
    public string $second;
    public string $date;

    public function __construct(CurrencyExchangeRateService $currencyExchangeRateService,
                                ExchangeRatesService $exchangeRatesService,
                                CurrencyExchangeRateRepository $currencyExchangeRateRepository)
    {
       $this->exchangeRatesService = $exchangeRatesService;
       $this->currencyExchangeRateService = $currencyExchangeRateService;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    #[Route('/exchange-rate', name: 'get_rate')]
    public function getRateByDate(Request $request): \Symfony\Component\HttpFoundation\JsonResponse
    {
        $base = $request->query->get('base');
        $second = $request->query->get('second');
//        $date = $request->query->get('date');
        $date = DateTime::createFromFormat('Y-m-d', $request->query->get('date'));

        $resultInDb = $this->currencyExchangeRateService
            ->getExchangeRateByDate($base, $second, $date);

        if ($resultInDb)
        {
            return $this->json($resultInDb);
        }
        else
        {
            return $this->json($this->exchangeRatesService
                ->getExchangeRateByDate($base, $second, $date));
        }

    }

    #[Route('/exchange-rates', name: 'get_rates')]
    public function getRatesByPeriod(Request $request): JsonResponse
    {
        $base = $request->query->get('base');
        $second = $request->query->get('second');
        $datePeriodFrom = DateTime::createFromFormat('Y-m-d', $request->query->get('datePeriodFrom'));
        $datePeriodTo = DateTime::createFromFormat('Y-m-d', $request->query->get('datePeriodTo'));

        return $this->json($this->currencyExchangeRateService
            ->getExchangeRateByPeriodDates($base, $second, $datePeriodTo, $datePeriodFrom));

    }

}

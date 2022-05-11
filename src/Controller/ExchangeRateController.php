<?php

namespace App\Controller;

use App\Exception\CurrencyExchangeRateNotDateException;
use App\Exception\CurrencyExchangeRateNotFoundException;
use App\Service\CurrencyExchangeRateService;
use App\Service\ExchangeRates\ExchangeRatesService;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class ExchangeRateController extends AbstractController
{
    public string $base;
    public string $second;
    public string $date;

    public function __construct(private CurrencyExchangeRateService $currencyExchangeRateService,
                                private ExchangeRatesService $exchangeRatesService
                                )
    {
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface|ExceptionInterface
     */

    #[Route('/exchange-rate', name: 'get_rate')]
    public function getRateByDate(Request $request): JsonResponse
    {
        $from = $request->query->get('base');
        $to = $request->query->get('second');
        $date = DateTime::createFromFormat('Y-m-d', $request->query->get('date'));

        if (!$date instanceof DateTime)
        {
            throw new CurrencyExchangeRateNotDateException();
        }

        try {
            $resultInDb = $this->currencyExchangeRateService
                ->getExchangeRateByDate($from, $to, $date);

            if (null == $resultInDb)
            {
                $resultInApi = $this->exchangeRatesService
                    ->getExchangeRateByDate($from, $to, $date);
            }else
            {
                return $this->json($resultInDb);
            }

            return $this->json($resultInApi);

        } catch (CurrencyExchangeRateNotFoundException $exception)
        {
            throw new HttpException($exception->getCode(), $exception->getMessage());
        }

    }

    #[Route('/exchange-rates', name: 'get_rates')]
    public function getRatesByPeriod(Request $request): JsonResponse
    {
        $from = $request->query->get('base');
        $to = $request->query->get('second');
        $datePeriodFrom = DateTime::createFromFormat('Y-m-d', $request->query->get('datePeriodFrom'));
        $datePeriodTo = DateTime::createFromFormat('Y-m-d', $request->query->get('datePeriodTo'));

        return $this->json($this->currencyExchangeRateService
            ->getExchangeRateByPeriodDates($from, $to, $datePeriodFrom, $datePeriodTo));

    }

}

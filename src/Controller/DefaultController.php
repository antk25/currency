<?php

namespace App\Controller;

use App\Repository\CurrencyExchangeRateRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    private CurrencyExchangeRateRepository $currencyExchangeRateRepository;
    public $base;
    public $second;
    public $date;

    public function __construct(CurrencyExchangeRateRepository $currencyExchangeRateRepository)
    {
       $this->currencyExchangeRateRepository = $currencyExchangeRateRepository;
    }

    #[Route('/rate', name: 'get_rate')]
    public function getRate(Request $request)
    {
        $base = $request->query->get('base');
        $second = $request->query->get('second');
        $date = DateTime::createFromFormat('Y-m-d', $request->query->get('date'));

        $response = $this->currencyExchangeRateRepository->findOneBy([
            'base' => $base,
            'second' => $second,
            'date' => $date,
        ]);

        if (!$response) {

            $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.apilayer.com/exchangerates_data/2020-05-16?symbols=RUB&base=EUR",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: text/plain",
                "apikey: O3GQOxAOYLdMQlJ0RX2M2FvH6OP59QLq"
            ),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET"
            ));

            $raw_response = curl_exec($curl);

            $array_response = json_decode($raw_response, true);

            $base = $array_response['base'];

            $second = $array_response['rates']['RUB'];

            $date = $array_response['date'];

            $result = [
                'base' => $base,
                'second' => $second,
                'date' => $date,
            ];

            return $this->json($result);

        }

        return $this->json($response);
    }

    #[Route('/default', name: 'app_default')]
    public function index(): Response
    {
        $rates = $this->currencyExchangeRateRepository->findAll();

        return $this->json($rates);
    }
}

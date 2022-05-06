<?php

namespace App\Service\ExchangeRates;

use App\Model\ExchangeRatesResponse;
use DateTime;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;


class ExchangeRatesService
{
    private HttpClientInterface $httpClientInterface;
    private SerializerInterface $serializer;

    public function __construct(HttpClientInterface $httpClientInterface, SerializerInterface $serializer)
    {
       $this->httpClientInterface = $httpClientInterface;
       $this->serializer = $serializer;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function getExchangeRateByDate(string $base, string $second, DateTime $date): array
    {
        $date = $date->format('Y-m-d');
        $response = $this->httpClientInterface
            ->request('GET', 'https://api.apilayer.com/exchangerates_data/'.$date.'?symbols='.$second.'&base='.$base,
            [
                'headers' => [
                    'Content-Type' => 'text/plain',
                    'apikey' => 'O3GQOxAOYLdMQlJ0RX2M2FvH6OP59QLq',
                ]

            ]
            );

        $array = json_decode($response->getContent(), true);

        $newarr = [
            'from' => $array['base'],
            'date' => $array['date'],
            'to' => array_key_first($array['rates']),
            'rate' => current($array['rates']),
        ];

//        $newarr = json_encode($newarr);

        return $newarr;

//        return $this->serializer->deserialize(
//            $newarr,
//            ExchangeRatesResponse::class,
//            JsonEncoder::FORMAT
//        );
    }
}
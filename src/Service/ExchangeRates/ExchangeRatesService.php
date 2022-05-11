<?php

namespace App\Service\ExchangeRates;

use App\Entity\CurrencyExchangeRate;
use DateTime;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\NameConverter\MetadataAwareNameConverter;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;


class ExchangeRatesService
{

    public function __construct(private HttpClientInterface $httpClientInterface)
    {
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws ExceptionInterface
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
//
        $result = [
            'from' => $array['base'],
            'date' => $array['date'],
            'to' => array_key_first($array['rates']),
            'rate' => current($array['rates']),
        ];
//
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));

        $metadataAwareNameConverter = new MetadataAwareNameConverter($classMetadataFactory);

        $serializer = new Serializer(
            [new ObjectNormalizer($classMetadataFactory, $metadataAwareNameConverter)]
        );

        return $serializer->normalize($result, null, [AbstractNormalizer::ATTRIBUTES
        => ['base', 'second', 'date', 'rate']]);

    }
}
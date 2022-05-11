<?php

namespace App\Service;

use App\Entity\CurrencyExchangeRate;
use App\Exception\CurrencyExchangeRateNotFoundException;
use App\Model\CurrencyExchangeRateResponse;
use App\Repository\CurrencyExchangeRateRepository;
use DateTime;
use Doctrine\Common\Annotations\AnnotationReader;
use http\Message;
use phpDocumentor\Reflection\PseudoTypes\NonEmptyLowercaseString;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\NameConverter\MetadataAwareNameConverter;

class CurrencyExchangeRateService
{

    public function __construct(private CurrencyExchangeRateRepository $currencyExchangeRateRepository)
    {
    }

    /**
     * @throws ExceptionInterface
     */
    public function getExchangeRateByDate(?string $from, ?string $to, DateTime $date): ?array
    {
        $exchangeRate = $this->currencyExchangeRateRepository
                ->findCurrencyExchangeRate($from, $to, $date);

        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));

        $metadataAwareNameConverter = new MetadataAwareNameConverter($classMetadataFactory);

        $serializer = new Serializer(
            [new ObjectNormalizer($classMetadataFactory, $metadataAwareNameConverter)]
        );

        return $serializer->normalize($exchangeRate, null, [AbstractNormalizer::ATTRIBUTES
                => ['base', 'second', 'date', 'rate']]);

    }

    public function getExchangeRateByPeriodDates(?string $from, ?string $to, DateTime $datePeriodFrom, DateTime $datePeriodTo): array
    {

        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));

        $metadataAwareNameConverter = new MetadataAwareNameConverter($classMetadataFactory);

        $serializer = new Serializer(
            [new ObjectNormalizer($classMetadataFactory, $metadataAwareNameConverter)]
        );

        $exchangeRates =  $this->currencyExchangeRateRepository
        ->findAllBetweenDates($from, $to, $datePeriodFrom, $datePeriodTo);

        return $serializer->normalize($exchangeRates, null, [AbstractNormalizer::ATTRIBUTES
        => ['base', 'second', 'date', 'rate']]);

    }
}
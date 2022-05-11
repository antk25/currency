<?php

namespace App\Repository;

use App\Entity\CurrencyExchangeRate;
use App\Repository\CurrencyExchangeRateRepository;
use App\Tests\AbstractRepositoryTest;
use DateTime;

class CurrencyExchangeRateRepositoryTest extends AbstractRepositoryTest
{
    private CurrencyExchangeRateRepository $currencyExchangeRateRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->currencyExchangeRateRepository = $this->getRepositoryForEntity(CurrencyExchangeRate::class);
    }

    public function testFindCurrencyExchangeRate()
    {

        $currencyExchangeRate = (new CurrencyExchangeRate())
            ->setBase('USD')
            ->setSecond('RUB')
            ->setDate(DateTime::createFromFormat('Y-m-d', '2022-05-05'))
            ->setRate(78.05);

        $this->em->persist($currencyExchangeRate);

        $this->em->flush();

        $this->assertCount(1, $this->currencyExchangeRateRepository
            ->findCurrencyExchangeRate('USD', 'RUB', DateTime::createFromFormat('Y-m-d', '2022-05-05')));
    }
}

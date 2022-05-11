<?php

namespace App\Tests\Functional\Controller;

use App\Entity\CurrencyExchangeRate;
use App\Tests\AbstractControllerTest;
use DateTime;
use Symfony\Component\HttpFoundation\Request;

class ExchangeRateControllerTest extends AbstractControllerTest
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testGetCurrencyExchangeRateByDate(): void
    {
        $this->em->persist((new CurrencyExchangeRate())
            ->setBase('USD')
            ->setSecond('RUB')
            ->setDate(DateTime::createFromFormat('Y-m-d', '2022-05-05'))
            ->setRate('78.08')
        );

        $this->em->flush();

        $this->client->request('GET', '/exchange-rate?base=USD&second=RUB&date=2022-05-05');

        $this->assertResponseIsSuccessful();

        $responseContent = $this->client->getResponse()->getContent();

        $this->assertJsonStringEqualsJsonFile(__DIR__ . '/responses/getCurrencyExchangeRate.json', $responseContent);

    }

}
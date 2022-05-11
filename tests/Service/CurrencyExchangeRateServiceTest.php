<?php

namespace App\Service;

use App\Entity\CurrencyExchangeRate;
use App\Repository\CurrencyExchangeRateRepository;
use DateTime;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class CurrencyExchangeRateServiceTest extends TestCase
{
    private MockObject $repository;

    public function setUp(): void
    {
        $this->repository = $this->createMock(CurrencyExchangeRateRepository::class);
    }

    /**
     * @throws ExceptionInterface
     */
    public function testGetExchangeRateByDate()
    {
        $currencyExchangeRate = [
            'from' => 'USD',
            'to' => 'RUB',
            'date' => '2022-05-05',
            'rate' => 78.08
        ];

        $this->repository->expects($this->once())
           ->method('findCurrencyExchangeRate')
           ->willReturn($currencyExchangeRate);

        $service = new CurrencyExchangeRateService($this->repository);

        $expected = [
            'from' => 'USD',
            'to' => 'RUB',
            'date' => '2022-05-05',
            'rate' => 78.08
        ];

        $this->assertEquals($expected, $service
            ->getExchangeRateByDate('USD', 'RUB', DateTime::createFromFormat('Y-m-d', '2022-05-05')));

    }
}

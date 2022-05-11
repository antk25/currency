<?php

namespace App\Model;

use Symfony\Component\Serializer\Annotation\SerializedName;

class CurrencyExchangeRateResponse

{
    #[SerializedName('from')]
    private string $base;
    private string $second;
    private string $date;
    private float $rate;

    public function __construct(string $base, string $second, string $date, float $rate)
    {
        $this->base = $base;
        $this->second = $second;
        $this->date = $date;
        $this->rate = $rate;
    }

    public function getBase(): string
    {
        return $this->base;
    }

    public function getSecond(): string
    {
        return $this->second;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function getRate(): float
    {
        return $this->rate;
    }
}
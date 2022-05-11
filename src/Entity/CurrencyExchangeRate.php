<?php

namespace App\Entity;

use App\Repository\CurrencyExchangeRateRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\SerializedName;

#[ORM\Entity(repositoryClass: CurrencyExchangeRateRepository::class)]
class CurrencyExchangeRate
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[SerializedName('from')]
    private ?string $base;

    #[ORM\Column(type: 'string', length: 255)]
    #[SerializedName('to')]
    private ?string $second;

    #[ORM\Column(type: 'date')]
    private ?DateTime $date;

    #[ORM\Column(type: 'float')]
    private ?float $rate;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBase(): ?string
    {
        return $this->base;
    }

    public function setBase(string $base): self
    {
        $this->base = $base;

        return $this;
    }

    public function getSecond(): ?string
    {
        return $this->second;
    }

    public function setSecond(string $second): self
    {
        $this->second = $second;

        return $this;
    }

    public function getDate(): ?string
    {
        return $this->date->format('Y-m-d');
    }

    public function setDate(DateTime $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getRate(): ?float
    {
        return $this->rate;
    }

    public function setRate(float $rate): self
    {
        $this->rate = $rate;

        return $this;
    }
}

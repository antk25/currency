<?php

namespace App\Entity;

use App\Repository\CurrencyExchangeRateRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CurrencyExchangeRateRepository::class)]
class CurrencyExchangeRate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $base;

    #[ORM\Column(type: 'string', length: 255)]
    private $second;

    #[ORM\Column(type: 'date')]
    private $date;

    #[ORM\Column(type: 'float')]
    private $rate;

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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
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

<?php

namespace App\DataFixtures;

use App\Entity\CurrencyExchangeRate;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class CurrencyExchangeRateFixtures extends Fixture
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 10; $i++) {
            $cer = new CurrencyExchangeRate();
            $cer->setBase($this->faker->randomElement(['USD', 'EUR', 'GBP']));
            $cer->setSecond($this->faker->randomElement(['RUB', 'JPY', 'CAD']));
            $cer->setDate($this->faker->dateTimeBetween('-1 week', '+1 week'));
            $cer->setRate($this->faker->randomFloat(2, 73.50, 87.50));
            $manager->persist($cer);
        }

        $manager->flush();
    }

}

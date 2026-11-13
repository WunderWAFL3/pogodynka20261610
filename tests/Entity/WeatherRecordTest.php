<?php

namespace App\Tests\Entity;

use App\Entity\WeatherRecord;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class WeatherRecordTest extends TestCase
{
    #[DataProvider('dataGetFahrenheit')]
    public function testGetFahrenheit(string $celsius, string $expectedFahrenheit): void
    {
        $measurement = new WeatherRecord();
        $measurement->setTemperature((float)$celsius);

        $this->assertEquals((float)$expectedFahrenheit, $measurement->getFahrenheit());
    }

    public static function dataGetFahrenheit(): array
    {
        return [
            ['0', '32'],
            ['-100', '-148'],
            ['100', '212'],
            ['0.5', '32.9'],
            ['10', '50'],
            ['20', '68'],
            ['-40', '-40'],
            ['37', '98.6'],
            ['25', '77'],
            ['-10', '14'],
        ];
    }
}

<?php

namespace App\Service;

use App\Entity\Place;
use App\Repository\WeatherRecordRepository;
use App\Repository\PlaceRepository;

class WeatherUtil
{
    public function __construct(
        private PlaceRepository $placeRepository,
        private WeatherRecordRepository $weatherRecordRepository,
    ) {
    }

    /**
     * @return WeatherRecord[]
     */
    public function getWeatherForPlace(Place $place): array
    {

        return $this->weatherRecordRepository->findBy(['place' => $place]);
    }

    /**
     * @return WeatherRecord[]
     */
    public function getWeatherForCountryAndCity(string $countryCode, string $city): array
    {

        $place = $this->placeRepository->findOneBy([
            'country' => $countryCode,
            'city' => $city,
        ]);

        if (!$place) {
            return [];
        }

        return $this->getWeatherForPlace($place);
    }
}

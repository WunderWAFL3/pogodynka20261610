<?php

namespace App\Controller;

use App\Entity\Place;
use App\Repository\PlaceRepository;
use App\Repository\WeatherRecordRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class WeatherController extends AbstractController
{
    ##[Route('/weather/{country}/{city}', name: 'app_weather')]
    public function city(string $country, string $city, PlaceRepository $placeRepository, WeatherRecordRepository $weatherRecordRepository): Response
    {
        $place = $placeRepository->findOneByCityAndCountry($city, $country);

        if (!$place) {
            throw $this->createNotFoundException('Localization not found');
        }

        $weatherRecords = $weatherRecordRepository->findByLocation($place);

        return $this->render('weather/city.html.twig', [
            'place' => $place,
            'weatherRecords' => $weatherRecords,
        ]);
    }

}

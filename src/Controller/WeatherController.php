<?php

namespace App\Controller;

use App\Entity\Place;
use App\Service\WeatherUtil;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class WeatherController extends AbstractController
{
    #[Route('/weather/{country}/{city}', name: 'app_weather')]
    public function city(
        #[MapEntity(mapping: ['country' => 'country', 'city' => 'city'])]
        Place $place,
        WeatherUtil $util,
    ): Response {

        $weatherRecords = $util->getWeatherForPlace($place);

        return $this->render('weather/city.html.twig', [
            'place' => $place,
            'weatherRecords' => $weatherRecords,
        ]);
    }
}

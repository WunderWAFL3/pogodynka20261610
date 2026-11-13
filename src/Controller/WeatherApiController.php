<?php

namespace App\Controller;

use App\Service\WeatherUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

class WeatherApiController extends AbstractController
{
    #[Route('/api/v1/weather', name: 'app_weather_api', methods: ['GET'])]
    public function index(
        #[MapQueryParameter] string $country,
        #[MapQueryParameter] string $city,
        #[MapQueryParameter] ?string $format = 'json',
        #[MapQueryParameter('twig')] bool $twig = false,
        WeatherUtil $weatherUtil,
    ): Response {
        $measurements = $weatherUtil->getWeatherForCountryAndCity($country, $city);

        if ($twig) {
            if ($format === 'csv') {
                return $this->render('weather_api/index.csv.twig', [
                    'city' => $city,
                    'country' => $country,
                    'measurements' => $measurements,
                ]);
            }

            return $this->render('weather_api/index.json.twig', [
                'city' => $city,
                'country' => $country,
                'measurements' => $measurements,
            ]);
        }

        if ($format === 'csv') {
            $csv = "city,country,date,celsius\n";
            foreach ($measurements as $m) {
                $csv .= sprintf(
                    "%s,%s,%s,%s\n",
                    $city,
                    $country,
                    $m->getDate()->format('Y-m-d'),
                    $m->getTemperature()
                );
            }

            return new Response($csv);
        }

        return $this->json([
            'city' => $city,
            'country' => $country,
            'measurements' => array_map(fn ($m) => [
                'date' => $m->getDate()->format('Y-m-d'),
                'celsius' => $m->getTemperature(),
                'fahrenheit' => $m->getFahrenheit(),
            ], $measurements),
        ]);
    }
}

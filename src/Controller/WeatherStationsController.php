<?php

namespace App\Controller;

use App\Service\WeatherStationsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class WeatherStationsController extends AbstractController
{
    #[Route('/weather-stations', name:'app_weather_station_list', methods: ['GET'])]
    public function getAllWeatherStations(WeatherStationsService $weatherStationsService): JsonResponse
    {
        $allWeatherStations = $weatherStationsService->getAllWeatherStations();
        return $this->json($allWeatherStations);
    }

    #[Route('/weather-stations/{stationId}', name:'app_weather_station_details', methods: ['GET'])]
    public function getWeatherStationByStationId(string $stationId, WeatherStationsService $weatherStationsService): JsonResponse
    {
        $weatherStation = $weatherStationsService->getWeatherStationById($stationId);
        return $this->json($weatherStation);
    }
}

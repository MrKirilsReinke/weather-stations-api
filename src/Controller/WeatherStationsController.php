<?php

namespace App\Controller;

use App\Service\WeatherStationsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;


class WeatherStationsController extends AbstractController
{
    #[Route('/api/weather-stations', name: 'app_weather_station_list', methods: ['GET'])]
    #[OA\Get(description: 'Returns a list of all weather stations')]
    #[OA\Response(
        response: 200,
        description: 'Returns a list of all stations with two properties: Station_id and Name',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(
                properties: [
                    new OA\Property(property: 'Station_id', type: 'string', description: 'The unique identifier for the station'),
                    new OA\Property(property: 'Name', type: 'string', description: 'The name of the station')
                ]
            )
        )
    )]
    #[OA\Response(
        response: 401,
        description: 'Unauthorized - This response occurs if:
        - No Bearer Token is provided.
        - An invalid Bearer Token is provided.',
        content: new OA\JsonContent(
            example: [
                "message" => "Authentication error. See description for details.",
                "error" => "Authentication required"
            ]
        )
    )]
    #[Security(name: 'Bearer')]
    public function getAllWeatherStations(WeatherStationsService $weatherStationsService): JsonResponse
    {
        $allWeatherStations = $weatherStationsService->getAllWeatherStations();
        return $this->json($allWeatherStations);
    }

    #[Route('/api/weather-stations/{stationId}', name: 'app_weather_station_details', methods: ['GET'])]
    #[OA\Get(description: 'Returns station details')]
    #[OA\Response(
        response: 200,
        description: 'Returns station details by Station_id with all data fields found in data source',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: '_id', type: 'number', description: 'The unique identifier for the station in data.gov.lv database'),
                new OA\Property(property: 'STATION_ID', type: 'string', description: 'The unique identifier for the station'),
                new OA\Property(property: 'NAME', type: 'string', description: 'The name of the station'),
                new OA\Property(property: 'WMO_ID', type: 'number', description: "Station's identification number registered in the World Meteorological Organization registry."),
                new OA\Property(property: 'BEGIN_DATE', type: 'string', description: 'The date when the first data from the station was registered'),
                new OA\Property(property: 'END_DATE', type: 'string', description: 'The date when the latest data from the station was registered. (Note: the date "31-12-3999 23:59:00" means that the station is still active.)'),
                new OA\Property(property: 'LATITUDE', type: 'number', description: 'The latitude coordinates of the station. The first two digits of the coordinates are in degrees, the second pair corresponds to minutes, and the third pair to seconds'),
                new OA\Property(property: 'LONGITUDE', type: 'number', description: 'The longitude coordinates of the station. The first two digits of the coordinates are in degrees, the second pair corresponds to minutes, and the third pair to seconds'),
                new OA\Property(property: 'GAUSS1', type: 'string', description: 'Coordinates in the EPSG:28403 system (longitude)'),
                new OA\Property(property: 'GAUSS2', type: 'string', description: 'Coordinates in the EPSG:28403 system (latitude)'),
                new OA\Property(property: 'GEOGR1', type: 'number', description: 'Station coordinates in the WGS84 reference system (longitude)'),
                new OA\Property(property: 'GEOGR2', type: 'number', description: 'Station coordinates in the WGS84 reference system (latitude)'),
                new OA\Property(property: 'ELEVATION', type: 'number', description: "The station's elevation above sea level in meters"),
                new OA\Property(property: 'ELEVATION_PRESSURE', type: 'number', description: "The station's barometer height above sea level in meters"),
            ]
        )
    )]
    #[OA\Parameter(
        name: 'stationId',
        in: 'path',
        required: true,
        description: 'The ID of the weather station',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Response(
        response: 401,
        description: 'Unauthorized - This response occurs if:
        - No Bearer Token is provided.
        - An invalid Bearer Token is provided.',
        content: new OA\JsonContent(
            example: [
                "message" => "Authentication error. See description for details.",
                "error" => "Authentication required"
            ]
        )
    )]
    #[OA\Response(
        response: 404,
        description: 'Not Found',
        content: new OA\JsonContent(
            example: [
                "message" => "Station with a provided id not found"
            ]
        )
    )]
    #[Security(name: 'Bearer')]
    public function getWeatherStationByStationId(string $stationId, WeatherStationsService $weatherStationsService): JsonResponse
    {
        $weatherStation = $weatherStationsService->getWeatherStationById($stationId);
        if ($weatherStation === null) {
            return $this->json(['message'=>'Station with a provided id not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($weatherStation);
    }
}

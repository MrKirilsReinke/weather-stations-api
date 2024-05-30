<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;


class WeatherStationsService
{
    public function __construct(
      private HttpClientInterface $client,
    ) {      
    }

    public function getAllWeatherStations(): array
    {
      $dataGovMeteoData = 'c32c7afd-0d05-44fd-8b24-1de85b4bf11d';

      try {
        $response = $this->client->request('GET', "https://data.gov.lv/dati/lv/api/3/action/datastore_search_sql?sql=SELECT * from \"$dataGovMeteoData\"");

        if ($response->getStatusCode() !== 200) {
          throw new \Exception("Failed to fetch data with status code: " . $response->getStatusCode());
        };

        $content = $response->toArray();

        return $this->processResponse($content);
      } catch(\Exception $e) {
        throw new \Exception("Following error occured:" . $e->getMessage());
      };
    }

    function processResponse(array $data): array 
    {
      if (isset($data['result']) && isset($data['result']['records'])) {
        foreach ($data['result']['records'] as $record) {
            $stations[] = [
                'Station_id' => $record['STATION_ID'],
                'Name' => $record['NAME']
            ];
        };
    };

    return $stations;
    }

    public function getWeatherStationById(string $stationId)
    {
      $dataGovMeteoData = 'c32c7afd-0d05-44fd-8b24-1de85b4bf11d';

      try {
        $stationId = strtoupper($stationId);
        $filters = sprintf('{"STATION_ID":"%s"}', $stationId);
        $url = sprintf("https://data.gov.lv/dati/api/3/action/datastore_search?resource_id=%s&filters=%s", $dataGovMeteoData, $filters);

        $response = $this->client->request('GET', $url);

        if ($response->getStatusCode() !== 200) {
          throw new \Exception("Failed to fetch data with status code: " . $response->getStatusCode());
        };

        $content = $response->toArray();

        if ($content['result']['total'] === 1) {
          return $content['result']['records'][0];

        } else { 

          return $content;
        }
      } catch(\Exception $e) {
        throw new \Exception("Following error occured:" . $e->getMessage());
      };
    }
};
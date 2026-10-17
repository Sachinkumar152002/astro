<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class SearchLocationService
{
    protected $client;

    public function __construct()
    {
        // Set up Guzzle client with the base URI
        $this->client = new Client([
            'base_uri' => optionByKey('astro_api_ip'),
            'timeout'  => 10.0,
        ]);
    }

    // Method to search for location by title
    public function searchLocation($title)
    {
        try {
            // Make a GET request to the Search API
            $response = $this->client->get('/api/Search', [
                'query' => ['title' => $title], // Pass the title as query parameter
                'headers' => [
                    'accept' => '*/*', // Accept header
                    'x-api-key' => optionByKey('astro_api_token')
                ]
            ]);

            // Decode the response body to get the data
            $responseBody = json_decode($response->getBody(), true);

            return $responseBody; // Return the API response

        } catch (RequestException $e) {
            // Handle the error response if any
            if ($e->hasResponse()) {
                $errorResponse = json_decode($e->getResponse()->getBody()->getContents(), true);
                return $errorResponse;
            }

            return ['error' => 'Something went wrong with the API call'];
        }
    }
}

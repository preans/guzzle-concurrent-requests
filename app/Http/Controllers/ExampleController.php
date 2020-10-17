<?php

namespace App\Http\Controllers;

use GuzzleHttp\Promise;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class ExampleController extends Controller
{
    /**
     * Sends requests sequentially
     */
    public function sequential()
    {
        $time_start = microtime(true);

        $client = new Client();

        $responses = [];

        $responses['archive'] = $client->get('https://archive.org/advancedsearch.php?q=subject:google+sheets&output=json');
        $responses['cat_facts'] = $client->get('https://cat-fact.herokuapp.com/facts');
        $responses['coin_gecko'] = $client->get('https://api.coingecko.com/api/v3/exchange_rates');
        $responses['universities'] = $client->get('http://universities.hipolabs.com/search?country=United+Kingdom');
        $responses['countries'] = $client->get('https://restcountries.eu/rest/v2/all');
        $responses['randomuser'] = $client->get('https://randomuser.me/api/');
        $responses['punkapi'] = $client->get('https://api.punkapi.com/v2/beers');
        $responses['publicapis'] = $client->get('https://api.publicapis.org/entries');
        $responses['openlibrary'] = $client->get('https://openlibrary.org/api/volumes/brief/isbn/9780525440987.json');
        $responses['food_facts'] = $client->get('https://world.openfoodfacts.org/api/v0/product/737628064502.json');

        foreach ($responses as $key => $result)
        {
            echo $key . "<br>";

            //$result is a Guzzle Response object
            //do stuff with $result
            //eg. $result->getBody()
        }

        echo 'Sequential execution time in seconds: ' . (microtime(true) - $time_start);
    }

    /**
     * Sends requests concurrently
     */
    public function concurrent()
    {
        $time_start = microtime(true);

        $client = new Client();

        $promises = [
            'archive' => $client->getAsync('https://archive.org/advancedsearch.php?q=subject:google+sheets&output=json'),
            'cat_facts'   => $client->getAsync('https://cat-fact.herokuapp.com/facts'),
            'coin_gecko'  => $client->getAsync('https://api.coingecko.com/api/v3/exchange_rates'),
            'universities'  => $client->getAsync('http://universities.hipolabs.com/search?country=United+Kingdom'),
            'countries'  => $client->getAsync('https://restcountries.eu/rest/v2/all'),
            'randomuser'  => $client->getAsync('https://randomuser.me/api/'),
            'punkapi'  => $client->getAsync('https://api.punkapi.com/v2/beers'),
            'publicapis'  => $client->getAsync('https://api.publicapis.org/entries'),
            'openlibrary'  => $client->getAsync('https://openlibrary.org/api/volumes/brief/isbn/9780525440987.json'),
            'food_facts'  => $client->getAsync('https://world.openfoodfacts.org/api/v0/product/737628064502.json'),
        ];

        $responses = Promise\Utils::settle($promises)->wait();

        foreach ($responses as $key => $response)
        {
            echo $key . "<br>";

            //response state is either 'fulfilled' or 'rejected'
            if($response['state'] === 'rejected')
            {
                //handle rejected
                continue;
            }

            //$result is a Guzzle Response object
            $result = $response['value'];

            //do stuff with $result
            //eg. $result->getBody()
        }

        echo 'Concurrent execution time in seconds: ' . (microtime(true) - $time_start);
    }
}

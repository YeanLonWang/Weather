<?php

/*
 * This file is part of the wangyanlong/weather.
 *
 * (c) wangyanlong <>
 * 
 * This source file is subject to the MIT license that is bundled.
 */

namespace Wangyanlong\Weather;

use GuzzleHttp\Client;
use Wangyanlong\Weather\Exceptions\HttpException;
use Wangyanlong\Weather\Exceptions\InvalidArgumentException;

class Weather
{
    protected $key;
    protected $guzzleOptions = [];

    public function __construct(string $key)
    {
        $this->key = $key;
    }

    public function getHttpClient()
    {
        return new Client($this->guzzleOptions);
    }

    public function setGuzzleOptions(array $options)
    {
        $this->guzzleOptions = $options;
    }

    public function getWeather($city, $type = 'base', $format = 'json')
    {
        $url = 'https://restapi.amap.com/v3/weather/weatherInfo';

        if(!\in_array(\strtolower($format), ['xml', 'json'])) {
            throw new InvalidArgumentException('invalid response format:'.$format);
        }

        if(!\in_array(\strtolower($type), ['base', 'all'])) {
            throw new InvalidArgumentException('invalid type value(base/all):'.$type);
        }

        $format = \strtolower($format);
        $type = \strtolower($type);

        $query = array_filter([
            'key' => $this->key,
            'city' => $city,
            'output' => $format,
            'extensions' => $type,
        ]);


        try{
            $response = $this->getHttpClient()->get($url, [
                'query' => $query,
            ])->getBody()->getContents();

            return 'json' === $format ? \json_decode($response, true) : $response;
        } catch (\Exception $e) {
            throw new HttpException($e->getMessage(), $e->getCode(), $e);
        }

    }

}

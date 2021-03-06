<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

use App\Models\LocationModel;

$app->get('/', function () use ($app) {
    $response = array(
        'status' => 'error',
        'message' => ''
    );

    $response['status'] = 'success';

    return response()->json($response);
});

$app->get('/unauthorized', ['as' => 'unauthorized', function () {
    $response = array(
        'status' => 'error',
        'message' => ''
    );

    $response['message'] = 'Access unauthorized';

    return response()->json($response, 401);
}]);


$app->get('/country/get[/{idOrName}]', function ($idOrName = null) {

    $response = array(
        'status'    => 'error',
        'message'   => '',
        'data'      => null
    );

    try {
        $LocationModel = new LocationModel(app('db'));
        if($idOrName) {
            if(is_numeric($idOrName)) {
                $idCountry = $idOrName;

                $country = $LocationModel->getCountryById($idCountry);
                $countries = $country;
            } else {
                $countryName = $idOrName;

                $countries = $LocationModel->getCountriesByName($countryName);
            }
        } else {
            $countries = $LocationModel->getAllCountries();
        }

        $response['status'] = 'success';
        $response['data']   = $countries;
    } catch (Exception $e) {
        $response['status']     = 'error';
        $response['message']    = $e->getMessage();
    }

    return response()->json($response);
});

/* Search of name in alternative names and Id */
$app->get('/city/get/{idOrName}[/{countryName}]', function ($idOrName = null, $countryName = null) {

    $response = array(
        'status'    => 'error',
        'message'   => '',
        'data'      => null
    );

    try {
        $LocationModel = new LocationModel(app('db'));

        if(is_numeric($idOrName)) {
            $idCity = $idOrName;

            $city   = $LocationModel->getCityById($idCity);
            $cities = $city;
        } else {
            $cityName = $idOrName;
            $cityName = urldecode($cityName);

            $countryName = urldecode($countryName);

            $cities = $LocationModel->getCitiesByAlternativeName($cityName, $countryName);
        }

        $response['status'] = 'success';
        $response['data']   = $cities;
    } catch (Exception $e) {
        $response['status']     = 'error';
        $response['message']    = $e->getMessage();
    }

    return response()->json($response);
});

/* Search for name in city names (NOT alternative names) */
$app->get('/city/get-by-name/{cityName}', function ($cityName = null) {

    $response = array(
        'status'    => 'error',
        'message'   => '',
        'data'      => null
    );

    try {
        $LocationModel = new LocationModel(app('db'));

        $cityName = trim($cityName);
        $cityName = urldecode($cityName);

        $cities = $LocationModel->getCitiesByName($cityName);

        $response['status'] = 'success';
        $response['data']   = $cities;
    } catch (Exception $e) {
        $response['status']     = 'error';
        $response['message']    = $e->getMessage();
    }

    return response()->json($response);
});
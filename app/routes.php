<?php namespace eBossApi;

/** @var \Herbert\Framework\Router $router */

/*$router->get([
'as'   => 'uploadcvRoute',
'uri'  => '/uploadcv/{uid}',
'uses' => __NAMESPACE__ . '\Controllers\FrontController@uploadcvPage'
]);*/

/*$router->get([
'as' => 'resultsRoute',
'uri' => '/result',
'uses' => __NAMESPACE__ . '\Controllers\FrontController@resultPage'
]);*/

$router->get([
    'as' => 'getRegion',
    'uri' => '/region',
    'uses' => __NAMESPACE__ . '\Controllers\FrontController@ajaxRegionAction'
]);

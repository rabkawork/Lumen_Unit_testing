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

// $router->get('/', function () use ($router) {
//     return $router->app->version();
// });

// Matches "/api/login
$router->group(['prefix' => 'api'], function () use ($router) 
{


	$router->post('login', 'AuthController@login');
	/**
		Templates 
	**/
	$router->get('checklists/templates', 'TemplatesController@index');
	$router->post('checklists/templates', 'TemplatesController@create');
	$router->get('checklists/templates/{templateId}', 'TemplatesController@getone');
	$router->patch('checklists/templates/{templateId}', 'TemplatesController@update');
	$router->delete('checklists/templates/{templateId}', 'TemplatesController@remove');
	$router->post('checklists/templates/{templateId}/assigns', 'TemplatesController@assign');

	/**
		Checklists 
	**/
	$router->get('checklists/{checklistId}', 'ChecklistsController@getone');
	$router->patch('checklists/{checklistId}', 'ChecklistsController@update');
	$router->delete('checklists/{checklistId}', 'ChecklistsController@remove');
	$router->post('checklists', 'ChecklistsController@create');
	$router->get('checklists', 'ChecklistsController@index');

	/**
		items
	**/
	$router->post('checklists/complete', 'ItemsController@complete');
	$router->post('checklists/incomplete', 'ItemsController@incomplete');
	$router->get('checklists/{checklistId}/items', 'ItemsController@listAllitems');
	$router->post('checklists/{checklistId}/items', 'ItemsController@createchecklistitem');

	$router->get('checklists/{checklistId}/items/{itemId}', 'ItemsController@getchecklistitem');
	$router->patch('checklists/{checklistId}/items/{itemId}', 'ItemsController@updatechecklistitem');
	$router->delete('checklists/{checklistId}/items/{itemId}', 'ItemsController@deletechecklistitems');
	$router->post('checklists/{checklistId}/items/_bulk', 'ItemsController@updatechecklistitemsbulk');
	
	// $router->get('checklists/items/sumaries', 'ItemsController@sumaries');
	// $router->get('checklists/items', 'ItemsController@getall');



	/**
		Histories
	**/
	// $router->get('checklists/histories', 'HistoriesController@index'); //
	// $router->get('checklists/histories/{historyId}', 'HistoriesController@getone'); //



});

   

<?php

namespace App\Http\Controllers;
use App\History;
use Illuminate\Http\Request;
use DB;
use URL;
//./vendor/bin/codecept run api ItemsTestCest --steps
class ItemsTestCest
{
    private $id;
    private $token;

    public function testComplete(\ApiTester $I)
	{
        /**
         *  initial login
         *  i writing this code, to get token
         */

        $sendJson = '{
            "data": [
              {
                "item_id": 1
              },
              {
                "item_id": 2
              },
              {
                "item_id": 3
              },
              {
                "item_id": 4
              }
            ]
          }';

        $I->wantToTest('Login');
		$I->sendPOST('api/login', ['email' => 'ah4d1an@gmail.com','password' => '123456789']);
		$response = json_decode($I->grabResponse());
        $this->token = $response->data->authorization->token;
        $I->haveHttpHeader('Content-Type','application/json');
 		$I->wantToTest('Complete');
        $I->amBearerAuthenticated($this->token);
        $I->sendPOST    ('api/checklists/complete', $sendJson);
        $I->seeResponseCodeIs(200); // 200
        // $I->seeResponseIsJson();
    }

    public function testIncomplete(\ApiTester $I)
	{


        $sendJson = '{
            "data": [
              {
                "item_id": 1
              },
              {
                "item_id": 2
              }
            ]
          }';
        $I->haveHttpHeader('Content-Type','application/json');
 		$I->wantToTest('Incomplete');
        $I->amBearerAuthenticated($this->token);
        $I->sendPOST('api/checklists/incomplete', $sendJson);
        $I->seeResponseCodeIs(200); // 200
        $I->seeResponseIsJson();
    }

    public function testListallItems(\ApiTester $I)
	{
        $I->haveHttpHeader('Content-Type','application/json');
 		$I->wantToTest('List All items in given checklists');
        $I->amBearerAuthenticated($this->token);
        $I->sendGET('api/checklists/1/items?filter&sort&fields&page_limit=10&page_offset=0', []);
        $I->seeResponseCodeIs(200); // 200
        $I->seeResponseIsJson();
    }

    public function testCreateChecklistItem(\ApiTester $I)
	{

        $sendJson = '{
            "data": {
              "attribute": {
                "description": "Need to verify this guy house.",
                "due": "2019-01-19 18:34:51",
                "urgency": "2",
                "assignee_id": 123
              }
            }
          }';

        $I->haveHttpHeader('Content-Type','application/json');
 		$I->wantToTest('Create Checklist Item');
        $I->amBearerAuthenticated($this->token);
        $I->sendPOST('api/checklists/1/items', $sendJson);
        $I->seeResponseCodeIs(200); // 200
        $I->seeResponseIsJson();
    }


    public function testCreateChecklistItemValidation(\ApiTester $I)
	{

        $sendJson = '{
            "data": {
              "attribute": {
                "description": "Need to verify this guy house.",
                "due": "2019-01-19 18:34:51",
                "urgency": "2",
                "assignee_id": 123
              }
            }
          }';

        $I->haveHttpHeader('Content-Type','application/json');
 		$I->wantToTest('Create Checklist Item');
        $I->amBearerAuthenticated($this->token);
        $I->sendPOST('api/checklists/1000/items', $sendJson);
        $I->seeResponseCodeIs(404); // 200
        $I->seeResponseIsJson();
    }



    public function testgetChecklistItemValidation(\ApiTester $I)
	{

        $I->haveHttpHeader('Content-Type','application/json');
 		$I->wantToTest('Get Checklist Item');
        $I->amBearerAuthenticated($this->token);
        $I->sendGET('api/checklists/1/items/200', []);
        $I->seeResponseCodeIs(404); // 200
        $I->seeResponseIsJson();
    }

    public function testgetChecklistItem(\ApiTester $I)
	{

        $I->haveHttpHeader('Content-Type','application/json');
 		$I->wantToTest('Get Checklist Item');
        $I->amBearerAuthenticated($this->token);
        $I->sendGET('api/checklists/1/items/1', []);
        $I->seeResponseCodeIs(200); // 200
        $I->seeResponseIsJson();
    }


    public function testUpdateChecklistItemValidation(\ApiTester $I)
	{

        $sendJson = '{
            "data": {
              "attribute": {
                "description": "Need to verify this guy house.",
                "due": "2019-01-19 18:34:51",
                "urgency": "2",
                "assignee_id": 123
              }
            }
          }';

        $I->haveHttpHeader('Content-Type','application/json');
 		$I->wantToTest('Update Checklist Item');
        $I->amBearerAuthenticated($this->token);
        $I->sendPATCH('api/checklists/1/items/1000', $sendJson);
        $I->seeResponseCodeIs(404); // 200
        $I->seeResponseIsJson();
    }

    public function testUpdateChecklistItem(\ApiTester $I)
	{
        $sendJson = '{
            "data": {
              "attribute": {
                "description": "Need to verify this guy house.",
                "due": "2019-01-19 18:34:51",
                "urgency": "2",
                "assignee_id": 123
              }
            }
          }';

        $I->haveHttpHeader('Content-Type','application/json');
 		$I->wantToTest('Update Checklist Item');
        $I->amBearerAuthenticated($this->token);
        $I->sendPATCH('api/checklists/1/items/1', $sendJson);
        $I->seeResponseCodeIs(200); // 200
        $I->seeResponseIsJson();
    }

    public function testDeleteChecklistItem(\ApiTester $I)
	{
        $I->haveHttpHeader('Content-Type','application/json');
 		$I->wantToTest('Update Checklist Item');
        $I->amBearerAuthenticated($this->token);
        $I->sendDELETE('api/checklists/1/items/1', []);
        $I->seeResponseCodeIs(200); // 200
        $I->seeResponseIsJson();
    }
}

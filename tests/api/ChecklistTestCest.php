<?php

namespace App\Http\Controllers;
use App\History;
use Illuminate\Http\Request;
use DB;
use URL;
//./vendor/bin/codecept run api ChecklistTestCest --steps
class ChecklistTestCest
{
    private $token;
    private $id;


  public function testCreateChecklists(\ApiTester $I)
	{

        $I->wantToTest('Login');
        $I->sendPOST('api/login', ['email' => 'ah4d1an@gmail.com','password' => '123456789']);
        $response = json_decode($I->grabResponse());
        $this->token = $response->data->authorization->token;
        
        $sendJson = '{
            "data": {
              "attributes": {
                "object_domain": "contact",
                "object_id": "3",
                "due": "2019-01-25T07:50:14+00:00",
                "urgency": 1,
                "description": "Need to verify this guy house.",
                "items": [
                  "Visit his house",
                  "Capture a photo",
                  "Meet him on the house"
                ],
                "task_id": "123"
              }
            }
          }';

        $I->haveHttpHeader('Content-Type','application/json');
        $I->wantToTest('CREATE Checklists');
        $I->amBearerAuthenticated($this->token);
        $I->sendPOST('api/checklists', $sendJson);
        $response = json_decode($I->grabResponse());
        // var_dump($response);
        $this->id = $response->data->id;     


        $I->seeResponseCodeIs(201); // 200
        $I->seeResponseIsJson();
  }

  public function testCreateChecklistsValidation(\ApiTester $I)
	{

        $sendJson = '{
            "data": {
              "attributes": {
                "object_domain": "",
                "object_id": "3",
                "due": "2019-01-25T07:50:14+00:00",
                "urgency": 1,
                "description": "Need to verify this guy house.",
                "items": [
                  "Visit his house",
                  "Capture a photo",
                  "Meet him on the house"
                ],
                "task_id": "123"
              }
            }
          }';

        $I->haveHttpHeader('Content-Type','application/json');
        $I->wantToTest('CREATE Checklists Validation');
        $I->amBearerAuthenticated($this->token);
        $I->sendPOST('api/checklists', $sendJson);
        $I->seeResponseCodeIs(404); // 200
        $I->seeResponseIsJson();
    }


    public function testUpdateChecklists(\ApiTester $I)
	  {

        $sendJson = '{
            "data": {
              "type": "checklists",
              "id": 1,
              "attributes": {
                "object_domain": "contact",
                "object_id": "1",
                "description": "Need to verify this guy house.",
                "is_completed": false,
                "completed_at": null,
                "created_at": "2018-01-25T07:50:14+00:00"
              },
              "links": {
                "self": "https://dev-kong.command-api.kw.com/checklists/50127"
              }
            }
          }';

        $I->haveHttpHeader('Content-Type','application/json');
        $I->wantToTest('Update Checklists');
        $I->amBearerAuthenticated($this->token);
        $I->sendPATCH('api/checklists/'.$this->id, $sendJson);
        $response = json_decode($I->grabResponse());
        $I->seeResponseCodeIs(200); // 200
        $I->seeResponseIsJson();
    }

    public function testUpdateChecklistsValidation(\ApiTester $I)
	  {

        $sendJson = '{
            "data": {
              "type": "checklists",
              "id": 1,
              "attributes": {
                "object_domain": "",
                "object_id": "1",
                "description": "Need to verify this guy house.",
                "is_completed": false,
                "completed_at": null,
                "created_at": "2018-01-25T07:50:14+00:00"
              },
              "links": {
                "self": "https://dev-kong.command-api.kw.com/checklists/50127"
              }
            }
          }';

        $I->haveHttpHeader('Content-Type','application/json');

        $I->wantToTest('UPDATE Checklists Validation');
        $I->amBearerAuthenticated($this->token);
        $I->sendPATCH('api/checklists/'.$this->id, $sendJson);
        $I->seeResponseCodeIs(404); // 200
        $I->seeResponseIsJson();
    }
    
    public function testGetChecklistById(\ApiTester $I)
	  {
 		$I->wantToTest('Get Checklist by Id');
        $I->amBearerAuthenticated($this->token);
        $I->sendGET('api/checklists/'.$this->id, []);
        $I->seeResponseCodeIs(200); // 200
        $I->seeResponseIsJson();
    }
    
    public function testGetChecklistByWrongId(\ApiTester $I)
	  {

        $I->wantToTest('Get Checklist with wrong id');
        $I->amBearerAuthenticated($this->token);
        $I->sendGET('api/checklists/1000', []);
        $I->seeResponseCodeIs(404); // 200
        $I->seeResponseIsJson();
    }

    public function testGetlistOfChecklists(\ApiTester $I)
	  {
        $I->wantToTest('Get List Of Checklists');
        $I->amBearerAuthenticated($this->token);
        $I->sendGET('api/checklists?filter&sort&fields&page_limit=10&page_offset=0', []);
        $I->seeResponseCodeIs(200); // 200
        $I->seeResponseIsJson();
    }


    public function testRemoveChecklists(\ApiTester $I)
	  {
        $I->wantToTest('Delete Checklist');
        $I->amBearerAuthenticated($this->token);
        $I->sendDELETE('api/checklists/'.$this->id, []);
        $I->seeResponseCodeIs(204); // 204
        // $I->seeResponseIsJson();
    }

    public function testRemoveChecklistsWrongId(\ApiTester $I)
	  {
        $I->wantToTest('Delete Checklist');
        $I->amBearerAuthenticated($this->token);
        $I->sendDELETE('api/checklists/10000', []);
        $I->seeResponseCodeIs(404); 
        $I->seeResponseIsJson();
    }




    
}

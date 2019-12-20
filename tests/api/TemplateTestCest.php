<?php

namespace App\Http\Controllers;
use App\History;
use Illuminate\Http\Request;
use DB;
use URL;
//./vendor/bin/codecept run api TemplateTestCest --steps
class TemplateTestCest
{
    private $token;
    private $id;


    public function testGetlistOfTemplates(\ApiTester $I)
	{

        $I->wantToTest('Login');
        $I->sendPOST('api/login', ['email' => 'ah4d1an@gmail.com','password' => '123456789']);
        $response = json_decode($I->grabResponse());
        $this->token = $response->data->authorization->token;

        $I->wantToTest('Get List Of Checklists');
        $I->amBearerAuthenticated($this->token);
        $I->sendGET('api/checklists/templates/?filter&sort&fields&page_limit=10&page_offset=0', []);
        $I->seeResponseCodeIs(200); // 200
        $I->seeResponseIsJson();
    }



    public function testCreateTemplates(\ApiTester $I)
	{
        $sendJson = '{
            "data": {
              "attributes": {
                "name": "foo template",
                "checklist": {
                  "description": "my checklist",
                  "due_interval": 3,
                  "due_unit": "hour"
                },
                "items": [
                  {
                    "description": "my foo item",
                    "urgency": 2,
                    "due_interval": 40,
                    "due_unit": "minute"
                  },
                  {
                    "description": "my bar item",
                    "urgency": 3,
                    "due_interval": 30,
                    "due_unit": "minute"
                  }
                ]
              }
            }
          }';

        $I->haveHttpHeader('Content-Type','application/json');
        $I->wantToTest('CREATE Checklist Template');
        $I->amBearerAuthenticated($this->token);
        $I->sendPOST('api/checklists/templates', $sendJson);
        $response = json_decode($I->grabResponse());
        $this->id = $response->data->id;
        $I->seeResponseCodeIs(201); // 200
        $I->seeResponseIsJson();
    }

    public function testCreateTemplatesValidation(\ApiTester $I)
	{

        $sendJson = '{
            "data": {
              "attributes": {
                "name": "",
                "checklist": {
                  "description": "my checklist",
                  "due_interval": 3,
                  "due_unit": "hour"
                },
                "items": [
                  {
                    "description": "my foo item",
                    "urgency": 2,
                    "due_interval": 40,
                    "due_unit": "minute"
                  },
                  {
                    "description": "my bar item",
                    "urgency": 3,
                    "due_interval": 30,
                    "due_unit": "minute"
                  }
                ]
              }
            }
          }';

        $I->haveHttpHeader('Content-Type','application/json');
        $I->wantToTest('CREATE Checklists/templates Validation');
        $I->amBearerAuthenticated($this->token);
        $I->sendPOST('api/checklists/templates', $sendJson);
        $I->seeResponseCodeIs(400); // 200
        $I->seeResponseIsJson();
     }


     public function testGetOneTemplates(\ApiTester $I)
     {
         $I->wantToTest('Get Checklist Template');
         $I->amBearerAuthenticated($this->token);
         $I->sendGET('api/checklists/templates/'.$this->id, []);
         $I->seeResponseCodeIs(200); // 200
         $I->seeResponseIsJson();
     }

     public function testGetOneTemplatesValidation(\ApiTester $I)
     {
         $I->wantToTest('Get Checklist Template');
         $I->amBearerAuthenticated($this->token);
         $I->sendGET('api/checklists/templates/1000101', []);
         $I->seeResponseCodeIs(404); // 200
         $I->seeResponseIsJson();
         $I->seeResponseContainsJson(['error' => 'Not Found']);
     }

     public function testUpdateTemplates(\ApiTester $I)
     {
         $sendJson = '{
             "data": {
               "name": "foo templates",
               "checklist": {
                 "description": "my checklist",
                 "due_interval": 10,
                 "due_unit": "hour"
               },
               "items": [
                 {
                   "description": "my foos item",
                   "urgency": 2,
                   "due_interval": 40,
                   "due_unit": "minute"
                 },
                 {
                   "description": "my bars item",
                   "urgency": 3,
                   "due_interval": 30,
                   "due_unit": "minute"
                 }
               ]
             }
           }';

         $I->haveHttpHeader('Content-Type','application/json');
         $I->wantToTest('Update Templates');
         $I->amBearerAuthenticated($this->token);
         $I->sendPATCH('api/checklists/templates/'.$this->id, $sendJson);
         $response = json_decode($I->grabResponse());
         $I->seeResponseCodeIs(200); // 200
         $I->seeResponseIsJson();
     }

    public function testUpdateTemplatesValidation(\ApiTester $I)
	{
        $sendJson = '{
            "data": {
              "name": "",
              "checklist": {
                "description": "my checklist",
                "due_interval": 10,
                "due_unit": "hour"
              },
              "items": [
                {
                  "description": "my foos item",
                  "urgency": 2,
                  "due_interval": 40,
                  "due_unit": "minute"
                },
                {
                  "description": "my bars item",
                  "urgency": 3,
                  "due_interval": 30,
                  "due_unit": "minute"
                }
              ]
            }
          }';

        $I->haveHttpHeader('Content-Type','application/json');
        $I->wantToTest('Update Templates');
        $I->amBearerAuthenticated($this->token);
        $I->sendPATCH('api/checklists/templates/10000', $sendJson);
        $response = json_decode($I->grabResponse());
        $I->seeResponseCodeIs(400); // 200
        $I->seeResponseIsJson();
    }


     public function testDeleteTemplates(\ApiTester $I)
     {
         $I->wantToTest('Delete Template');
         $I->amBearerAuthenticated($this->token);
         $I->sendDELETE('api/checklists/templates/'.$this->id, []);
         $I->seeResponseCodeIs(204); // 200

         $response = json_decode($I->grabResponse());
        //  echo json_encode($response);
     }

     public function testDeleteTemplatesValidation(\ApiTester $I)
     {
         $I->wantToTest('Delete Template');
         $I->amBearerAuthenticated($this->token);
         $I->sendDELETE('api/checklists/templates/100101', []);
         $I->seeResponseCodeIs(404); // 200
         $I->seeResponseIsJson();
        //  $I->seeResponseContainsJson(['error' => 'Not Found']);
     }









}

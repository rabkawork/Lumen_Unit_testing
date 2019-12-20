<?php

namespace App\Http\Controllers;
use App\History;
use Illuminate\Http\Request;
use DB;
use URL;

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
        $I->wantToTest('CREATE Templates');
        $I->amBearerAuthenticated($this->token);
        $I->sendPOST('api/checklists', $sendJson);
        $response = json_decode($I->grabResponse());
        // var_dump($response);
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
        $I->seeResponseCodeIs(404); // 200
        $I->seeResponseIsJson();
    }
}

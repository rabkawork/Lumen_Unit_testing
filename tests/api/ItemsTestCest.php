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
        $I->sendPOST('api/checklists/complete', $sendJson);
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
}

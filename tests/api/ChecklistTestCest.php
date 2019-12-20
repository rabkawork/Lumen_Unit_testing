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

    
    public function testGetChecklistById(\ApiTester $I)
	{
        
        $I->wantToTest('Login');
		$I->sendPOST('api/login', ['email' => 'ah4d1an@gmail.com','password' => '123456789']);
		$response = json_decode($I->grabResponse());
        $this->token = $response->data->authorization->token;

 		$I->wantToTest('Get Checklist by Id');
        $I->amBearerAuthenticated($this->token);
        $I->sendGET('api/checklists/1', []);
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
}

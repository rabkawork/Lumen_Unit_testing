<?php 

use \ApiTester;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
//./vendor/bin/codecept run api HistoriesTestCest --steps
class HistoriesTestCest
{

    private $token;

    public function testGetHistoryById(\ApiTester $I)
	{
        /**
         *  initial login
         *  i writing this code, to get token 
         */
        $I->wantToTest('Login');
		$I->sendPOST('api/login', ['email' => 'ah4d1an@gmail.com','password' => '123456789']);
		$response = json_decode($I->grabResponse());
        $this->token = $response->data->authorization->token;

 		$I->wantToTest('Get History by Id');
        $I->amBearerAuthenticated($this->token);
        $I->sendGET('api/_checklists/histories/1', []);
        $I->seeResponseCodeIs(200); // 200
        $I->seeResponseIsJson();
    }
    


    public function testGetHistoryByIdWrongId(\ApiTester $I)
	{
 		$I->wantToTest('Get History by Id with wrong history id');
        $I->amBearerAuthenticated($this->token);
        $I->sendGET('api/_checklists/histories/100', []);
        $I->seeResponseCodeIs(404); // 200
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['error' => 'Not Found','status' => 404]);
    }


    public function testGetListOfHistory(\ApiTester $I)
	{

 		$I->wantToTest('Get History by Id');
        $I->amBearerAuthenticated($this->token);
        $I->sendGET('api/_checklists/histories/?filter&sort&fields&page_limit=10&page_offset=0', []);
        $I->seeResponseCodeIs(200); // 200
        $I->seeResponseIsJson();
    }

    
}

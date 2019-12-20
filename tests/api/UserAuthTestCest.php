<?php 
//./vendor/bin/codecept run api UserAuthTestCest --steps

class UserAuthTestCest
{

	/***
		Login Test
	**/
    // tests
	public function testLoginIsSuccess(\ApiTester $I)
	{
 		$I->wantToTest('Login test');
	    // $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPOST('api/login', ['email' => 'ah4d1an@gmail.com', 'password' => '123456789']);
        $I->seeResponseCodeIs(200); // 200
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['message' => 'Login Success']);
        $I->seeResponseContainsJson(['email' => 'ah4d1an@gmail.com']);
	}

	public function testLoginIsFailed(\ApiTester $I)
	{
	    $I->wantToTest('Login failed');
	    // $I->haveHttpHeader('content-type', 'application/json');
	    $I->sendPOST('api/login', ['email' => 'ahadian@gmail.coms', 'password' => 'rahasia12311']);
	    $I->seeResponseCodeIs(404);
	    $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['message' => 'Email Or Password Not Found']);
	}


	public function testLoginIsValidated(\ApiTester $I)
	{
	    $I->wantToTest('Login failed');
	    // $I->haveHttpHeader('content-type', 'application/json');
	    $I->sendPOST('api/login', ['email' => '', 'password' => 'rahasia12311']);
	    $I->seeResponseCodeIs(411);
	    $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['message' => 'Login Failed!']);
	}



}

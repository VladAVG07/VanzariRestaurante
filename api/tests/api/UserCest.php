<?php

namespace api\tests;

use api\tests\ApiTester;

class UserCest {

    public function _before(ApiTester $I) {
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
    }

    // tests
    public function createAccountWithoutEmail(ApiTester $I) {
        $I->sendPOST('users', [
            //'email' => 'lord@lord.ro',
            'password' => '12345678',
            'type' => 'antrenor',
        ]);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $I->seeResponseContains('"message":"«Email» nu poate fi gol."');
    }

    public function createAccountWithoutPassword(ApiTester $I) {
        $I->sendPOST('users', [
            'email' => 'lord@lord.ro',
            //'password' => '12345678',
            'type' => 'antrenor',
        ]);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $I->seeResponseContains('"message":"«Password» nu poate fi gol."');
    }

    public function createAccount(ApiTester $I) {
        $I->sendPOST('users', [
            'email' => 'lord@lord.ro',
            'password' => '12345678',
            'type' => 'antrenor',
        ]);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $I->seeResponseContains('"message":"Created"');
    }

    public function existentAccount(ApiTester $I) {
        $I->sendPOST('users', [
            'email' => 'balea_cata@yahoo.com',
            'password' => '12345678',
            'type' => 'antrenor',
        ]);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $I->seeResponseContains('este deja ocupat');
    }

}

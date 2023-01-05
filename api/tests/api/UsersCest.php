<?php

namespace api\tests;

use api\tests\ApiTester;

class UserCest {

    public function _before(ApiTester $I) {
        $I->haveHttpHeader('apikey', 'aF2pHdPRiYthtmbr2dygQqBwhugufu85');
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->haveHttpHeader('device-uuid', 'ca10bb6490ff6102');
    }

    public function adaugaDisponibilitate(ApiTester $I) {
        $I->sendPOST('users/add-disponibilitate', [
            'zi_saptamana' => 5,
            'ora' => '11:30',
            'durata' => 60,
            'sala' => 8,
        ]);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $I->seeResponseContains('"zi_saptamana":5');
    }

    public function adaugaRezervare(ApiTester $I) {
        $I->haveHttpHeader('apikey', 'hKjctHESM6nle05Mf7Liu2u4P0U3iO7n');
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->haveHttpHeader('device-uuid', 'ca10bb6490ff6102');
        $I->sendPOST('users/add-rezervare', [
            'zi' => 5,
            'ora' => '11:30',
            'personal_trainer_serviciu' => 5,
        ]);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $I->seeResponseContains('"zi":5');
    }

    public function editeazaDisponibilitate(ApiTester $I) {
        $I->sendPOST('users/edit-disponibilitate/15', [
            //'id'=>15,
            'zi_saptamana' => 6,
            'ora' => '11:30',
            'durata' => 60,
            'sala' => 8,
        ]);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $I->seeResponseContains('"zi_saptamana":6');
    }

    public function stergeDisponibilitate(ApiTester $I) {
        $I->sendDELETE('users/delete-disponibilitate/15');
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $I->seeResponseContains('"message":"OK"');
    }

}

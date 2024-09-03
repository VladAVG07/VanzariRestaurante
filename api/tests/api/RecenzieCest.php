<?php

namespace api\tests;

use api\tests\ApiTester;

class RecenzieCest {

    public function _before(ApiTester $I) {
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->haveHttpHeader('apikey', '0FFtSxoDKlWq-f5KEzy_sK0vque27RAX');
    }

    public function adaugaRecenzie(ApiTester $I) {
        $I->sendPOST('recenzie', [
            'review_party' => 45,
            'review_party_type' => 1,
            'titlu' => 'test',
            'descriere' => 'descriere test',
            'rating' => 5,
        ]);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $I->seeResponseContains('"rating":5');
    }

    public function editeazaRecenzie(ApiTester $I) {
        $I->sendPOST('recenzie', [
            'review_party' => 45,
            'review_party_type' => 1,
            'titlu' => 'test',
            'descriere' => 'editeaza descriere test',
            'rating' => 5,
        ]);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $I->seeResponseContains('"descriere":"editeaza descriere test"');
    }

}

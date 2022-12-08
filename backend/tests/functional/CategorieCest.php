<?php

namespace backend\tests\functional;

use backend\tests\FunctionalTester;
//use common\fixtures\UserFixture;
use \Codeception\Util\Locator;
use backend\tests\functional\LoginCest;

/**
 * Class LoginCest
 */
class CategorieCest
{

    public function addCategorie(FunctionalTester $I) {
        $login = new LoginCest();
        $login->loginUser($I);


    }


}

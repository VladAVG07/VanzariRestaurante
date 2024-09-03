<?php

namespace backend\tests\functional;

use backend\tests\FunctionalTester;
use common\fixtures\UserFixture;
use \Codeception\Util\Locator;

/**
 * Class LoginCest
 */
class LoginCest
{
    /**
     * Load fixtures before db transaction begin
     * Called in _before()
     * @see \Codeception\Module\Yii2::_before()
     * @see \Codeception\Module\Yii2::loadFixtures()
     * @return array
     */
//    public function _fixtures()
//    {
//        return [
//            'user' => [
//                'class' => UserFixture::class,
//                'dataFile' => codecept_data_dir() . 'login_data.php'
//            ]
//        ];
//    }
    
    /**
     * @param FunctionalTester $I
     */
    public function loginUser(FunctionalTester $I)
    {
        $I->amOnRoute('/site/login');
        $I->fillField('LoginForm[username]', 'admin');
        $I->fillField('LoginForm[password]', '12345678');
        $I->click('login-button');

//        $I->see('.invalid-feedback');

//        $I->see('Logout (admin)', 'form button[type=submit]');
        $I->seeElement('i', ['class' => 'fas fa-sign-out-alt']);
//        $I->dontSeeLink('Login');
//        $I->seeElement('.fas fa-sign-out-alt');
//        $I->dontSeeLink('Signup');
//        $I->seeElement(Locator::find('a', ['name' => 'logout']));
//        $I->see( 'Starter Page', 'title');
    }

    public function logOutUser(FunctionalTester $I) {
        $this->loginUser($I);
        $I->amOnRoute('/site/index');
        $I->click(Locator::find('a', ['name' => 'logout']));
        $I->amOnRoute('/site/login');
    }
}

<?php

namespace backend\tests\Functional;

use backend\tests\FunctionalTester;
use \Codeception\Util\Locator;

//php vendor/bin/codecept run functional CategoriiCestTCest -c backend

class CategoriiCestTCest {

    public function _before(FunctionalTester $I) {
        $I->amOnRoute('/site/login');
        $I->fillField('LoginForm[username]', 'admin');
        $I->fillField('LoginForm[password]', '12345678');
        $I->click('login-button');
    }

    // tests
    public function tryToAddCategorie(FunctionalTester $I) {
        $I->amOnPage('index-test.php?r=categorii/index');
        $I->see('Categorii');
        //$I->see(Locator::isID('#add-categoriess'));
        // $I->click('Adauga Categorie', 'a');
        $I->click(['link' => 'Adauga Categorie']);
        $I->fillField('Categorii[nume]', 'nume');
        $I->fillField('Categorii[descriere]', 'descriere');
        $I->selectOption('Categorii[parinte]', 'Mancare');
        $I->checkOption('Categorii[valid]', 1);
        $I->click('Salveaza');
    }

    public function tryToDeleteCategorie(FunctionalTester $I) {
        $I->amOnPage('index-test.php?r=categorii/index');
        $I->click('Delete');
    }
    
    public function tryToEditCategorie(FunctionalTester $I){
        $I->amOnPage('index-test.php?r=categorii/index');
        $I->click('Update');
        $I->fillField('Categorii[nume]', 'nume');
        $I->fillField('Categorii[descriere]', 'descriere');
        $I->selectOption('Categorii[parinte]', 'Mancare');
        $I->click('Salveaza');
    }

}

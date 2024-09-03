<?php


namespace backend\tests\Functional;

use backend\tests\FunctionalTester;

class ProduseCest
{
    public function _before(FunctionalTester $I)
    {
        $I->amOnRoute('/site/login');
        $I->fillField('LoginForm[username]', 'admin');
        $I->fillField('LoginForm[password]', '12345678');
        $I->click('login-button');
    }

    // tests
    public function tryToSeeAllProduse(FunctionalTester $I)
    {
        $I->amOnPage('index-test.php?r=produse/index');
    }

    public function tryToAddProdusWithNoDataSfarsitPret(FunctionalTester $I) {
        $name = 'produs';
        $I->amOnPage('index-test.php?r=produse/create');
        $I->fillField(['name' => 'Produse[nume]'] , $name);
        $I->fillField(['name' => 'Produse[descriere]'] , 'descriere');
        $I->selectOption('Produse[categorie]' , 'pizza');
        $I->fillField(['name' => 'Produse[cod_produs]'] , 12312);
        $I->fillField(['name' => 'PreturiProduse[pret]'] , 34.55);
        $I->fillField(['name' => 'data_productie-produse-data_productie-disp'] , '12.12.2022 09:45');
        $I->fillField(['name' => 'data_inceput-preturiproduse-data_inceput-disp'], '12.12.2022 09:45');
        $I->click('Salveaza');
        $I->see($name , 'h1');
    }

    public function tryToAddProdusWithDataSfarsitPret(FunctionalTester $I) {
        $name = 'produs';
        $I->amOnPage('index-test.php?r=produse/create');
        $I->fillField(['name' => 'Produse[nume]'] , $name);
        $I->fillField(['name' => 'Produse[descriere]'] , 'descriere');
        $I->selectOption('Produse[categorie]' , 'pizza');
        $I->fillField(['name' => 'Produse[cod_produs]'] , 12312);
        $I->fillField(['name' => 'PreturiProduse[pret]'] , 34.55);
        $I->fillField(['name' => 'data_productie-produse-data_productie-disp'] , '12.12.2022 09:45');
        $I->fillField(['name' => 'data_inceput-preturiproduse-data_inceput-disp'], '12.12.2022 09:45');
        $I->fillField(['name' => 'data_sfarsit-preturiproduse-data_sfarsit-disp'] , '12.12.2022 09:45');
        $I->click('Salveaza');
        $I->see($name , 'h1');
    }

    public function tryToUpdateProdus(FunctionalTester $I) {
        $name = 'produsUpdatat';
        $I->amOnPage('index-test.php?r=produse/index');
        $I->click(['link' => 'Update']);
        $I->fillField(['name' => 'Produse[nume]'] , $name);
        $I->fillField(['name' => 'Produse[descriere]'] , 'descriere');
        $I->selectOption('Produse[categorie]' , 'pizza');
        $I->fillField(['name' => 'Produse[cod_produs]'] , 12312);
        $I->fillField(['name' => 'PreturiProduse[pret]'] , 34.55);
        $I->fillField(['name' => 'data_productie-produse-data_productie-disp'] , '12.12.2022 09:45');
        $I->fillField(['name' => 'data_inceput-preturiproduse-data_inceput-disp'], '12.12.2022 09:45');
        $I->fillField(['name' => 'data_sfarsit-preturiproduse-data_sfarsit-disp'] , '12.12.2022 09:45');
        $I->click('Salveaza');
        $I->see($name , 'h1');
    }

    public function testProdusError(FunctionalTester $I) {
        $I->amOnPage('index-test.php?r=produse/create');
        $I->click('Salveaza');
        $I->see('cannot be blank.' , 'div');
    }
}

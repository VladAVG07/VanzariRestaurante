<?php


namespace backend\tests\Unit;

use backend\models\Categorii;
use backend\tests\UnitTester;

class CategoriiTest extends \Codeception\Test\Unit
{

    protected UnitTester $tester;

    protected function _before()
    {
    }

    // tests
    public function testAddCategorieWithNoParinte()
    {
        $model = new Categorii();

        $model->nume = "test_name";
        $model->descriere = "test_descriere";
        $model->parinte = null;
        $model->valid = 1;

        verify($model->save())->true();
    }

    public function testAddCategorieWithParinte() {
        $parinte = new Categorii();

        $parinte->nume = "test_name";
        $parinte->descriere = "test_descriere";
        $parinte->parinte = null;
        $parinte->valid = 1;

        $parinte->save();

        $model = new Categorii();

        $model->nume = "test_name";
        $model->descriere = "test_descriere";
        $model->parinte = $model->id;
        $model->valid = 1;

        verify($model->save());
    }
}

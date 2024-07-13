<?php

namespace common\reports;

class MyReport extends \koolreport\KoolReport
{
    use \koolreport\yii2\Friendship;
    // By adding above statement, you have claim the friendship between two frameworks
    // As a result, this report will be able to accessed all databases of Yii2
    // There are no need to define the settings() function anymore
    // while you can do so if you have other datasources rather than those
    // defined in Laravel.


    function setup()
    {
        $this->src("default")
            ->query("SELECT * FROM produse")
            ->pipe($this->dataStore("produse"));
    }
}

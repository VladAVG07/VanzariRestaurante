<?php

namespace common\reports;

class RaportIncasari extends \koolreport\KoolReport
{
    use \koolreport\yii2\Friendship;


    function setup()
    {
        $this->src("default")
            ->query("SELECT ID, NUMAR_COMANDA AS \"Numar Comanda\", PRET FROM comenzi WHERE PRET >= :data")
            ->params(array(
                ":data" => $this->params["data"]
            ))
            ->pipe($this->dataStore("comenzi"));
    }
}

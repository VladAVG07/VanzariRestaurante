<?php

namespace backend\reports\vanzari;

use \koolreport\KoolReport;
use \koolreport\processes\Filter;
use \koolreport\processes\TimeBucket;
use \koolreport\processes\Group;
use \koolreport\processes\Limit;

class VanzariReport extends \koolreport\KoolReport
{
    public function settings()
    {
        return array(
            "dataSources"=>array(
                "app_db"=>array(
                    "connectionString"=>"mysql:host=localhost;dbname=restaurant",
                    "username"=>"root",
                    "password"=>"",
                    "charset"=>"utf8"
                )
            ),
            "assets"=>array(
                "path"=>"../web/assets",
                "url"=>"assets"
            )
        );
    }   
    protected function setup()
    {
        $this->src('app_db')
        ->query("select c.id,c.data_ora_creare, c.numar_comanda,c.pret,c.canal, cs.nume as status from comenzi c,comenzi_detalii cd, comenzi_statusuri cs 
where cd.comanda=c.id and cd.status=cs.id and cs.nume='Finalizata'")
        //->pipe(new TimeBucket(array(
       //     "payment_date"=>"month"
       // )))
        //->pipe(new Group(array(
        //    "by"=>"payment_date",
        //    "sum"=>"amount"
       // )))
        ->pipe($this->dataStore('vanzari_totale'));
    } 

}
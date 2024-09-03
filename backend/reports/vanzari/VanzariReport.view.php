<?php

use \koolreport\widgets\koolphp\Table;

//use \koolreport\widgets\google\ColumnChart;
?>

<div class="report-content">
    <div class="text-center">
        <h1>MySQL Report</h1>
        <p class="lead">This report show how to build report from MySQL data</p>
    </div>

    <?php
    Table::create(array(
        "dataStore" => $this->dataStore('vanzari_totale'),
        "columns" => array(
            "id" => array(
                "label" => "Id",
                "type" => "number",
            ),
            "numar_comanda" => array(
                "label" => "Numar comanda",
                "type" => "number",
            ),
            "pret" => array(
                "label" => "Pret",
                "type" => "number",
                "type" => "number",
                "prefix" => "RON"
            ),
            "data_ora_creare" => array(
                "label" => "Data/ora creare",
                "type" => "datetime",
                "format" => "Y-n",
                "displayFormat" => "F, Y",
            ),
            "canal" => array(
                "label" => "Canal",
                "type" => "string",
            ),
            "status" => array(
                "label" => "Stare",
                "type" => "string",
            ),
        ),
        "cssClass" => array(
            "table" => "table table-hover table-bordered"
        )
    ));
    ?>
</div>
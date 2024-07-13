<?php

use \koolreport\widgets\koolphp\Table;
?>
<html>

<body>
    <div class="container-fluid">
        <h1>Raport Incasari</h1>
        <?php
        Table::create([
            "dataSource" => $this->dataStore("comenzi"),
            "showFooter" => true,
            "columns" => array(
                "ID" => array(
                    "footer" => "count",
                    "footerText" => "<b>Nr total comenzi:</b> @value"
                ),
                "Numar Comanda",
                "PRET" => array(
                    "footer" => "sum",
                    "footerText" => "<b>Total:</b> @value",
                    "formatValue" => function ($value, $row) {
                        return "<span>" . $value . " RON</span>";
                    }
                )
            ),
            "cssClass" => array(
                "table" => "table-bordered table-striped table-hover"
            ),
            "paging" => array(
                "pageSize" => 10,
                "pageIndex" => 0,
            ),
        ]);
        ?>
    </div>
</body>

</html>
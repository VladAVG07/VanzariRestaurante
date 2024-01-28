<?php

use yii\helpers\Html;

$subcategorii = \backend\models\Categorii::getSubcategories($id);

if (!$subcategorii) {
    ?>
    <div class="col-sm-12 card">
        <div class="nav-tabs-custom card-header p-2">
            <br>
            <h3 style="text-align: center">Nu exista subcategorii</h3>
            <br>
        </div>
    </div>
    <?php
} else {
    ?>
    <div class="col-sm-12 card">
        <div class="nav-tabs-custom card-header p-2">
            <ul class="nav nav-tabs nav-pills">
                <?php
                $subcategorii = \backend\models\Categorii::getSubcategories($id);
                if (!$subcategorii) {
                    
                }
                $x = 0;
                $active = 'active';
                $expanded = true;
                //                                $c = new \backend\models\Categorii();
                //                                $c->nume = 'Rezultate cautare';
                //                                $c->id=-1;
                echo Html::tag('li', Html::a('Rezultate cautare', sprintf('#%s', 'rezultate-cautare'), ['data-toggle' => 'tab', 'data-id' => -1, 'aria-expanded' => $expanded, 'class' => 'taba nav-link']), ['style' => 'display:none', 'id' => 'rezultate-cautare1']);

                foreach ($subcategorii as $categorie) {
                    $class = sprintf('class="%s"', $active);
                    if ($x > 0) {
                        $active = '';
                        $expanded = false;
                    }
                    // echo Html::a('AICI');
                    echo Html::tag('li', Html::a($categorie->nume, sprintf('#%s', yii\helpers\Inflector::slug($categorie->nume)), ['data-toggle' => 'tab', 'data-id' => $categorie->id, 'aria-expanded' => $expanded, 'class' => 'taba nav-link']), ['class' => $active]);

                    $x++;
                }
                ?>
            </ul>
            <div class="test1">
                <div class="loading-overlay" id="loadingOverlay1">
                    <div class="loading-spinner"></div>
                </div>
                <div class="tab-content">
                    <?php
                    $x = 0;
                    echo Html::tag('div', Html::tag('div', '', ['class' => 'box-body']), ['class' => 'tab-pane', 'id' => 'rezultate-cautare']);

                    foreach ($subcategorii as $categorie) {
                        //  echo Html::a('AICI');
                        echo Html::tag('div', Html::tag('div', '', ['class' => 'box-body']), ['class' => 'tab-pane ' . ($x > 0 ? '' : 'active'), 'id' => yii\helpers\Inflector::slug($categorie->nume)]);
                        $x++;
                    }
                    ?>

                </div>
            </div>
        </div>
    </div>
    <?php
}
?>

<?php

use kartik\switchinput\SwitchInput;
use yii\helpers\Html;

$i = 0;
foreach ($model->produse_detalii as $produsDetaliuForm) {
    $idDisponibil = sprintf('%s-%s', Html::getInputId($produsDetaliuForm, 'disponibil'), $i);
?>

    <div class="row produs-record">
        <div class="col-md-6">
            <?= $form->field($produsDetaliuForm, "[$i]descriere")->textInput(['maxlength' => true])
            ->input( "[$i]descriere",['placeholder' => "32cm(300gr)"]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($produsDetaliuForm, "[$i]pret")->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-3">
            <div class="row align-items-center">
                <div class="col-md-auto">
                    <?=
                        $form->field($produsDetaliuForm, "[$i]disponibil")->widget(SwitchInput::class, [
                            'options' => [
                                'class' => 'form-control', 'id' => $idDisponibil,
                              //  'value' => $produsDetaliuForm->disponibil
                            ],
                            'pluginOptions' => [
                                'onText' => 'Da',
                                'offText' => 'Nu',
                            ]
                        ]);
                    ?>
                </div>
                <div class="col-md-auto">
                    <button type="button" attr-data="<?=$i?>" class="btn btn-danger btn-block btn-xs"><i class="fa fa-trash-alt"></i></button>
                </div>
            </div>
        </div>
    </div>
<?php
    $i++;
}
?>
<div class="row ">
    <div class="col-md-12 align-items-center">
        <div class="col-md-auto">
            <a id="add-prod-detalii" class="btn btn-primary">Adauga produs detaliu</a>
        </div>
    </div>
</div>
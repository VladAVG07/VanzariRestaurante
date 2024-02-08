<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\models\Categorii;
use kartik\datetime\DateTimePicker;
use \kartik\datecontrol\DateControl;
use yii\helpers\VarDumper;
use kartik\switchinput\SwitchInput;


/* @var $this yii\web\View */
/* @var $model backend\models\Produse */
/* @var $modelPret backend\models\PreturiProduse */
/* @var $form yii\bootstrap4\ActiveForm */

$js = <<< SCRIPT
        
     $(document).on('switchChange.bootstrapSwitch', function(e, s) {
       
        if (e.target.id==='produse-stocabil'){
          var data=$(".stoc-produs");
         
          if(s)
            data.show();
          else
            data.hide();
        }
    });
SCRIPT;
$this->registerJs($js, \yii\web\View::POS_READY);
?>

<div class="produse-form">

    <?php
    
    $form = ActiveForm::begin([
                'options' => ['name' => 'produse-form']
                    ]
    );
    ?>

    <?php
    $categorii = Categorii::find()
                ->innerJoin('restaurante_categorii rc', 'rc.categorie = categorii.id')
                ->innerJoin('restaurante r', 'rc.restaurant = r.id')
                ->innerJoin('restaurante_user ru', 'ru.restaurant = r.id')
                ->innerJoin('user u', 'ru.user = u.id')
                ->where(['u.id' => \Yii::$app->user->id])
               // ->select(['id', 'nume', 'parinte'])
                ->orderBy(['parinte' => SORT_ASC])->all();
    ?>

    <div class="row">
        <div class="col-md-4">
            <?=
            $form->field($model, 'categorie')->dropDownList(
                    Categorii::formatItemsArray($categorii), ['prompt' => 'Selecteaza categoria']
            )
            ?>
        </div>
        <div class="col-md-6">

            <?= $form->field($model, 'nume')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'cod_produs')->textInput() ?>
        </div>
    </div>


    <div class="row">

        <div class="col-md-6">
            <?= $form->field($model, 'descriere')->textArea(['maxlength' => true]) ?>
        </div>

        <div class="col-md-6">
            <?=
            $form->field($model, 'data_productie')->widget(DateControl::class, [
                'type' => DateControl::FORMAT_DATE,
                'displayFormat' => 'php:d.m.Y',
                //'saveFormat' => 'php:U',
                'widgetOptions' => [
                    'pluginOptions' => [
                        'autoclose' => true
                    ]
                ],
                'language' => 'ro'
            ]);
            ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'pret')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?=
            $form->field($model, 'dataInceputPret')->widget(DateControl::class, [
                'type' => DateControl::FORMAT_DATE,
                'displayFormat' => 'php:d.m.Y',
                'widgetOptions' => [
                    'pluginOptions' => [
                        'autoclose' => true
                    ]
                ],
                'language' => 'ro'
            ])
            ?>
        </div>

        <div class="col-md-4">
            <?=
            $form->field($model, 'dataSfarsitPret')->widget(DateControl::class, [
                'type' => DateControl::FORMAT_DATE,
                'displayFormat' => 'php:d.m.Y',
                'widgetOptions' => [
                    'pluginOptions' => [
                        'autoclose' => true
                    ]
                ],
                'language' => 'ro'
            ])
            ?>
        </div>

    </div>

    <div class="row">
        <div class="col-md-1">
            <?=
            $form->field($model, 'stocabil')->widget(SwitchInput::class, [
                "pluginEvents" => [
                    "init.bootstrapSwitch" => "function() { console.log('init'); }",
                    "switchChange.bootstrapSwitch" => "function() {console.log('switchChanged');  }",
                ],
                'pluginOptions' => [
                    'onText' => 'Da',
                    'offText' => 'Nu',
                ]
            ]);
            ?>
        </div>
        <?php
//        if($model->stocabil){
//        echo is_null($model->stocabil);
        ?>
        <div class="col-md-2 stoc-produs" style="<?= $model->stocabil === 0 ? "display:none" : "" ?>">
            <?= $form->field($model, 'alerta_stoc')->textInput(['maxlength' => true]) ?>
        </div>
        <?php
//            }
        ?>
    </div>

    <div class="row">
        <div class="col-md-1">
            <?=
            $form->field($model, 'disponibil')->widget(SwitchInput::class, [
                'pluginOptions' => [
                    'onText' => 'Da',
                    'offText' => 'Nu',
                ]
            ]);
            ?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-5">
            <?= 
                $form->field($model, 'imageFile')->fileInput(['id' => 'imageFile']);
            
                $this->registerJs("
                    $('#imageFile').change(function(){
                        console.log('intrat');
                        var file = this.files[0];
                        if (file) {
                            var reader = new FileReader();
                            reader.onload = function (e) {
                                $('#imagePreview').html('<img src=\"' + e.target.result + '\" class=\"img-preview\" style=\"max-width: 70%; max-height: auto;\" />');
                            }
                            reader.readAsDataURL(file);
                        } else {
                            $('#imagePreview').html('');
                        }
                    });
                ");
            
            ?>
        </div>
        <div class="col-md-7 form-group">
            <label>Previzualizare imagine</label>
            <div id="imagePreview"></div>
        </div>
    </div>


    <br>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Salveaza'), ['class' => 'btn btn-success right float-right']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

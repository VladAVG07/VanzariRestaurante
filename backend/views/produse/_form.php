<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\models\Categorii;
use kartik\datetime\DateTimePicker;
use \kartik\datecontrol\DateControl;
use yii\helpers\VarDumper;
use kartik\switchinput\SwitchInput;
use dosamigos\ckeditor\CKEditor;
use kartik\file\FileInput;


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
    ]);
    ?>

    <?php
    var_dump($model->errors);
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
                    Categorii::formatItemsArray($categorii),
                    ['prompt' => 'Selecteaza categoria']
                )
            ?>
        </div>
        <div class="col-md-5">

            <?= $form->field($model, 'nume')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'pret_curent')->textInput(['maxlength' => true]) ?>
        </div>
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
        <!-- <div class="col-md-2">
            <?= $form->field($model, 'cod_produs')->textInput() ?>
        </div> -->
    </div>


    <div class="row">

        <div class="col-md-auto">
            <?= $form->field($model, 'descriere')->widget(CKEditor::class, [
                'options' => ['rows' => 6],
                'preset' => 'basic' // You can choose different presets such as 'basic', 'standard', 'full'
            ]); ?>
            <div class=row">
            
        </div>
            <!-- <?=
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
            ?> -->
        </div>

        <div class="col-md-auto">
        <?=
               // $form->field($model, 'imageFile')->fileInput(['id' => 'imageFile']);
                 $form->field($model, 'imageFile')->widget(FileInput::classname(), [
                    'options' => ['accept' => 'image/*'],
                    'pluginOptions' => [
                        'maxFileCount' => 1,
                        'overwriteInitial'=> true,
                        'showClose'=> false,
                        //browseLabel: '',
                        //removeLabel: '',
                        //browseIcon: '<i class="bi-folder2-open"></i>',
                        //removeIcon: '<i class="bi-x-lg"></i>',
                        //removeTitle: 'Cancel or reset changes',
                        //elErrorContainer: '#kv-avatar-errors-1',
                        //msgErrorClass: 'alert alert-block alert-danger',
                        //defaultPreviewContent: '<img src="/samples/default-avatar-male.png" alt="Your Avatar">',
                        //layoutTemplates: {main2: '{preview} ' +  btnCust + ' {remove} {browse}'},
                        //allowedFileExtensions: ["jpg", "png", "gif"]
                        'showCaption' => false,
                        'showUpload' => false,
                        'showCancel'=>false,
                        'showRemove'=>true,
                        'browseClass' => 'btn btn-primary',
                        'browseIcon' => '<i class="fas fa-camera"></i> ',
                        'browseLabel' =>  'Selectati imaginea',
                        "initialPreviewShowDelete"=> false,
                        'initialPreview'=> [
                           !is_null($model->image_file)?sprintf('/backend/uploads/produse/%s',$model->image_file):null,
                        ],
                        'initialPreviewConfig' =>[
                            ['caption'=>$model->nume]
                        ],
                        'fileActionSettings'=> [
                            'showRemove'=>false,'showRotate'=>false,
                        ],
                        'initialPreviewAsData'=> true, // defaults markup
                    ],
                ]);

            // $this->registerJs("
            //         $('#imageFile').change(function(){
            //             console.log('intrat');
            //             var file = this.files[0];
            //             if (file) {
            //                 var reader = new FileReader();
            //                 reader.onload = function (e) {
            //                     $('#imagePreview').html('<img src=\"' + e.target.result + '\" class=\"img-preview img-thumbnail\" />');
            //                 }
            //                 reader.readAsDataURL(file);
            //             } else {
            //                 $('#imagePreview').html('');
            //             }
            //         });
            //     ");

            ?>
            
        </div>
    </div>

    <div class="row ">
       
        <div class="col-md-3 d-none">
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

        <div class="col-md-3 d-none">
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
        <div class="col-md-1 d">
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


    <br>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Salveaza'), ['class' => 'btn btn-success right float-right']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
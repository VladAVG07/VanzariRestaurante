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

    var pm=$(".produs-multiplu");
    var ps=$(".produs-simplu");
    var form = $('form');


    form.on('submit', function(event) {
        // Prevent the default form submission behavior
       // event.preventDefault();
    
        // Your custom code here to handle form submission
        
       // var errors = form.yiiActiveForm('data');

        // Log errors to the console
        //console.log('here',errors);
    
        // Optionally, you can submit the form programmatically if needed
        // form.submit();
    });

    function cloneAndModifyObject(originalObject, newIndex) {
        // Clone the original object
        var clonedObject = Object.assign({}, originalObject);
    
        // Modify properties with index in their names
        if (clonedObject.id) {
            clonedObject.id = clonedObject.id.replace(/\d+/, newIndex);
        }
        if(clonedObject.status){
            clonedObject.status=0;
        }
        if (clonedObject.name) {
            clonedObject.name = clonedObject.name.replace(/\[\d+\]/, '[' + newIndex + ']');
        }
        if (clonedObject.input) {
            clonedObject.input = clonedObject.input.replace(/\d+/, newIndex);
        }
        if (clonedObject.container) {
            clonedObject.container = clonedObject.container.replace(/\d+/, newIndex);
        }
    
        return clonedObject;
    }
    
    $(document).on('click', '.produs-record button', function(e) {
        e.preventDefault(); // Prevent the default action of the button click
        const id=$(this).attr('attr-data');

        console.log('search by ',id);
        var itemsToRemove=[];
        var settings=form.yiiActiveForm('data').attributes;
        settings.forEach(function(item) {
            //console.log('current item ',item.id);
            if(item.id && item.id.indexOf(id)!==-1){
                console.log('remove by id ',item.id);
                itemsToRemove.push(item.id);
            }
        });
        itemsToRemove.forEach(function(item){
            form.yiiActiveForm('remove',item);
        });
        settings=form.yiiActiveForm('data').attributes;
        settings.forEach(function(item){
            console.log(item.id,'fucking problem is here',item.status);
        });
        // Find the parent div with the class produs-record and remove it
        $(this).closest('.produs-record').remove();
    });

    $(document).on('click', '#add-prod-detalii', function(e){
        e.preventDefault(); // Prevent the default action of the anchor
        var lastRecord = $('.produs-record').last();
        // Clone the last record
        var clonedRecord = lastRecord.clone();
        //var form = $('form');
        var validationRules = [];
        clonedRecord.find('input').each(function() {
            //get old id, name and index values
            var oldId = $(this).attr('id');
            var oldName = $(this).attr('name');
            var oldIndex = parseInt(oldName.match(/\d+/)); // Extract the index from the old NAME
            
            var newIndex = oldIndex + 1; // Increment the index by 1
            clonedRecord.find('button').removeClass('d-none').attr('attr-data',newIndex);
            if(oldId){
                var label = $('label[for="' + oldId + '"]');
                $(this).attr('id', oldId.replace(oldIndex, newIndex));
                label.attr('for',$(this).attr('id'));
                $(this).parent().removeClass('field-'+oldId).addClass('field-'+ $(this).attr('id'));
                const settings=form.yiiActiveForm('data').attributes;
                settings.forEach(function(item) {
                    console.log(item.id);
                    if(item.id===oldId){
                        const clonedII=cloneAndModifyObject(item, newIndex);
                        validationRules.push(clonedII);
                    }
                });

            }
            const newName=oldName.replace(oldIndex, newIndex);
            $(this).attr('name', newName);
            $(this).removeClass();
            $(this).addClass('form-control');
            if($(this).attr('type')!=='hidden'){
                $(this).val('');
            }
        });
        clonedRecord.insertAfter(lastRecord);
        // Append the cloned and modified record
        clonedRecord.find('input').each(function(){
            if($(this).attr('type')==='checkbox')
            {
                var parent=$(this).parent();
                var clThis=$(this).clone();
                if(parent && parent.hasClass('bootstrap-switch-container')){
                    var opts = window[clThis.attr('data-krajee-bootstrapSwitch')];
                    var formGroupDiv=parent.parent().parent();
                    parent.parent().bootstrapSwitch('destroy');
                    parent.parent().remove();
                    formGroupDiv.append(clThis);
                    clThis.bootstrapSwitch(opts); // reinitialize switch input
                }
            }
        });
        validationRules.forEach(function(item){
            form.yiiActiveForm('add',item);
        });
    });

    $(document).ready(function() {
     //   pm.hide();
       // ps.show(); 
    });
     $(document).on('switchChange.bootstrapSwitch', function(e, s) {
       console.log(e.target.id,s);
       /*if(e.target.id==='produse-tip_produs'){
            if(!s){
                pm.show();
                ps.hide();
            }
            else{
                pm.hide();
                ps.show();
            }   
       }*/
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
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-6">
                    <?=
                        $form->field($model, 'categorie')->dropDownList(
                            Categorii::formatItemsArray($categorii),
                            ['prompt' => 'Selecteaza categoria']
                        )
                    ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'nume')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?= $form->field($model, 'descriere')->widget(CKEditor::class, [
                        'options' => ['rows' => 6],
                        'preset' => 'basic' // You can choose different presets such as 'basic', 'standard', 'full'
                    ]); ?>
                </div>
            </div>
            <div class="row">
                <!-- <div class="col-md-3">
                    <?= $form->field($model, 'tip_produs')->widget(SwitchInput::class, [
                        'pluginOptions' => [
                            'onText' => 'Simplu',
                            'offText' => 'Multiplu',
                        ]
                    ]);
                    ?>
                </div> -->
                <div class="col-md-12">
                    <!-- <div class="row produs-simplu">
                        <div class="col-md-4">
                            <?= $form->field($model, 'pret_curent')->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col-md-8">
                            <?=
                                $form->field($model, 'disponibil')->widget(SwitchInput::class, [
                                    'pluginOptions' => [
                                        'onText' => 'Da',
                                        'offText' => 'Nu',
                                    ]
                                ]);
                            ?>
                        </div>
                    </div> -->
                    <div class="row produs-multiplu">
                        <?php
                        echo $this->render('_tipuri_produs', ['model' => $model, 'form' => $form]);
                        ?>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-md-3">
            <div class="row">


                <div class="col-md-auto">
                    <?=
                        // $form->field($model, 'imageFile')->fileInput(['id' => 'imageFile']);
                        $form->field($model, 'imageFile')->widget(FileInput::classname(), [
                            'options' => ['accept' => 'image/*'],
                            'pluginOptions' => [
                                'maxFileCount' => 1,
                                'overwriteInitial' => true,
                                'showClose' => false,
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
                                'showCancel' => false,
                                'showRemove' => true,
                                'browseClass' => 'btn btn-primary',
                                'browseIcon' => '<i class="fas fa-camera"></i> ',
                                'browseLabel' =>  'Selectati imaginea',
                                "initialPreviewShowDelete" => false,
                                'initialPreview' => [
                                    !is_null($model->image_file) ? sprintf('/backend/uploads/produse/%s', $model->image_file) : null,
                                ],
                                'initialPreviewConfig' => [
                                    ['caption' => $model->nume]
                                ],
                                'fileActionSettings' => [
                                    'showRemove' => false, 'showRotate' => false,
                                ],
                                'initialPreviewAsData' => true, // defaults markup
                            ],
                        ]);
                    ?>

                </div>
            </div>
        </div>
    </div>



    <br>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Salveaza'), ['class' => 'btn btn-success right float-right']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
    $script = <<< JS
    $('#w0').on('afterValidate', function (event, messages, errorAttributes) {
            //console.log('afterValidate');
            if (errorAttributes.length) {
                //alert('Verifique os errors!'); 
            }
            $.each(messages, function(index, element) {
            if(element.length > 0) {
                console.log(element);
               // alert(element);
            }
        });
    });
    JS;
    $this->registerJs($script);
  ?>
<?php

namespace api\modules\v1\controllers;

class DeviceController extends BasicApiController {

    public $modelClass = 'api\modules\v1\models\Devices';

    public function behaviors() {
        $behaviors = parent::behaviors();
        unset($behaviors['authenticator']);
        unset($behaviors['access']);
        return $behaviors;
    }

    public function actions() {
        $actions = parent::actions();
        unset($actions['create']);
        return $actions;
    }

    public function actionCreate() {
        $model = new $this->modelClass;
        /* @var $model \api\modules\v1\models\Devices */
        $model->load(\Yii::$app->request->post(),'');
        
        $existentDevice = \api\modules\v1\models\Devices::findOne(['uuid'=>$model->uuid]);
        /* @var $existentDevice \api\modules\v1\models\Devices */
        if (is_null($existentDevice)) {
            $model->save();
            return $model;
        } else {
            $existentDevice->device_type=$model->device_type;
            $existentDevice->device_os=$model->device_os;
            $existentDevice->os_system_name=$model->os_system_name;
            $existentDevice->device_version=$model->device_version;
            $existentDevice->device_manufactured=$model->device_manufactured;
            $existentDevice->device_push_token=$model->device_push_token;
            $existentDevice->device_time_zone=$model->device_time_zone;
            $existentDevice->device_display=$model->device_display;
            $existentDevice->save();
            return $existentDevice;
        }
    }
    
//    public function actionSubscriptions(){
//         $deviceUuid = $request->getHeaders()->get('device-uuid');
//         $device= \api\modules\v1\models\Devices::findOne(['uuid'=>$deviceUuid]);
//         if(is_null($device))
//             return $device;
//    }
    

}

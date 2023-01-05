<?php

namespace api\modules\v1\controllers;

use yii\data\ActiveDataProvider;

class UserController extends BApiController {

//
////    public function behaviors() {
////        $behaviors = parent::behaviors();
////        unset($behaviors['authenticator']);
////        unset($behaviors['access']);
////        return $behaviors;
////    }
//
//    public function actions() {
//        $actions = parent::actions();
//        unset($actions['create']);
//        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
//
//        return $actions;
//    }
//
//    public function prepareDataProvider() {
//        $modelClass = $this->modelClass;
//        $query = $modelClass::find();
//        $query->innerJoin('auth_assignment aa', 'aa.user_id=user.id');
//        $query->where(['aa.item_name' => 'personal_trainer', 'user.status' => 10]);
//        return $dataProvider = new ActiveDataProvider([
//            'query' => $query,
//        ]);
//    }
//
//    public function actionCreate() {
//        $model = new $this->modelClass;
//        /* @var $model \api\modules\v1\models\Devices */
//        if (\Yii::$app->request->post('tip') === \backend\models\AuthItem::ROLE_PERSONAL_TRAINER) {
//            $model->scenario = \Yii::$app->request->post('tip');
//        }
//        $model->attributes = \Yii::$app->request->post();
//        $existentUser = \api\modules\v1\models\Users::findOne(['email' => $model->email]);
//
//        /* @var $existentUser \api\modules\v1\models\Users */
//        if (is_null($existentUser)) {
//            if ($model->saveUser(true))
//                return $model;
//            else
//                return $model;
//        } else {
//            $existentUser->attributes = \Yii::$app->request->post();
//            $existentUser->name = $model->name;
//            $existentUser->account_type = $model->account_type;
//            if (isset($model->phone) && !empty($model->phone)) {
//                $existentUser->phone = $model->phone;
//            }
//            $existentUser->saveUser(false);
//            return $existentUser;
//        }
//        return $model;
//    }
//
    public function actionLogin($user, $pass) {
        $us = new \api\modules\v1\models\Users();
        $uss = $us->login($user, $pass);
        return $uss;
    }

//
//    public function actionEdit() {
//        $apiKey = \Yii::$app->request->getHeaders()->get('apikey');
//        $status = [];
//        //check if the authenticate user can edit this data
//        $user = \api\modules\v1\models\Users::findOne(['auth_key' => $apiKey]);
//        if (is_null($user)) {
//            \Yii::$app->response->statusCode = 401;
//        } else {
//            $profile = $user->profile;
//            $profile->scenario = 'edit';
//            $profile->attributes = \Yii::$app->request->post();
//            // var_dump(\Yii::$app->request->post());
//            if ($profile->validate() && $profile->save()) {
//                $profile->savePhoto();
//            } else {
//                $user->addErrors($profile->errors);
//            }
//            $status = $user;
//        }
//        return $status;
//    }
//
//    protected function disableCheckAccessActions() {
//        return ['create', 'index'];
//    }
//
//    public function actionAddDisponibilitate() {
//        $model = new \api\modules\v1\models\PTDisponibilitate();
//        $model->attributes = \Yii::$app->request->post();
//        $model->personal_trainer = \Yii::$app->user->identity->profil->personalTrainerProfil->id;
//        $model->save();
//        $model->refresh();
//        return $model;
//    }
//
//    public function actionAddRezervare() {
//        $model = new \api\modules\v1\models\Rezervari();
//        $model->attributes = \Yii::$app->request->post();
//        if($model->save()){
//            $model->refresh();
//        }
//        return $model;
//    }
//
//    public function actionEditDisponibilitate($id) {
//        $model = \api\modules\v1\models\PTDisponibilitate::findOne($id);
//        if (is_null($model)) {
//            return [];
//        }
//        $model->attributes = \Yii::$app->request->post();
//        $model->save();
//        $model->refresh();
//        return $model;
//    }
//
//    public function actionDeleteDisponibilitate($id) {
//        \api\modules\v1\models\PTDisponibilitate::updateAll(['end_date' => date('Y-m-d', time())], ['id' => $id]);
//        return [];
//    }
    protected function initializeModelClass() {
        $this->modelClass = 'api\modules\v1\models\Users';
    }

    protected function disableCheckAccessActions() {
        return ['login', 'create'];
    }

    public function actionTrimiteInvitatie() {
        //todo:: verificare daca cel care trimite invitatia este sportiv, iar cel care primeste invitatia este antrenor
        $invitatie = new \backend\models\Invitatii();
        $invitatie->attributes = \Yii::$app->request->post();
        $invitatie->save();
        $invitatie->refresh();
        return $invitatie;
    }

    public function actionTrateazaInvitatie() {
        $trateazaInvitatie=new \api\modules\v1\models\TrateazaInvitatie();
        $trateazaInvitatie->attributes = \Yii::$app->request->post();
        if($trateazaInvitatie->validate()){
            $invitatie= \backend\models\Invitatii::findOne($trateazaInvitatie->invitation_id);
            if(is_null($trateazaInvitatie->motiv)){
                $invitatie->acceptata=1;
                $invitatie->motiv=null;
            }
            else{
                $invitatie->acceptata=0;
                $invitatie->motiv=$trateazaInvitatie->motiv;
            }
            if($invitatie->save()){
                \Yii::$app->response->setStatusCode(200,'Invitatie tratata cu succes');
            }
            return $invitatie;
        }
        return $trateazaInvitatie;
        
    }

    public function checkAccess($action, $model = null, $params = array()) {
        if ($action === 'view' || $action === 'update' || $action === 'delete') {
            /* @var $model \backend\models\Dosar */
            if ($model->id !== \Yii::$app->user->id) {
                throw new \yii\web\ForbiddenHttpException('We record all hacking attempts');
            }
        }
    }

}

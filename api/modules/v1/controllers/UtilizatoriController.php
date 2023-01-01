<?php

namespace api\modules\v1\controllers;

use api\modules\v1\models\User;
use yii\helpers\VarDumper;
use yii\rest\ActiveController;

class UtilizatoriController extends ActiveController
{
    public $modelClass = 'api\modules\v1\models\User';

    public function actionLogin()
    {
        $user = User::findByEmail(\Yii::$app->request->post('email'));
        if (!$user || !$user->validatePassword(\Yii::$app->request->post('password'))) {
            $user->addError('login','Combinatia formata din email si parola este incorecta');
        }
        return $user;
    }

    public function actionChangePassword() {
        $user= new User();
        $user->attributes = \Yii::$app->request->post();
        $user->new_password = \Yii::$app->request->post('new_password');

        $userDB = User::findByEmail($user->email);
        if(!is_null($userDB) && $userDB->validatePassword($user->password)) {
            $userDB->scenario = User::SCENARIO_CHANGE_PASS;
            $userDB->new_password = $user->new_password;
            $userDB->password = $user->password;
            $userDB->setPassword($user->new_password);
            if($userDB->validate()) {
                $userDB->generateAuthKey();
                $userDB->save();
                return $userDB;
            } else {
                $user->addError('change-pass' , 'Error saving');
                return $user;
            }
        }
        else {
            $user->addError('change-pass' , 'Invalid credentials!');
        }
        return $user;
    }
}
<?php

namespace api\modules\v1\filters;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CustomKeyAuthentication
 *
 * @author Marian
 */
class CustomLogin extends \yii\filters\auth\AuthMethod {

    /**
     * @inheritdoc
     */
    public function authenticate($user, $request, $response) {
        $username = $request->get('username');
        $password=$request->get('password');
        if ($username !== null) {
            $user=  \api\modules\v1\models\User::login($username, $password);
            $identity = $user->findIdentity($user->id);
            if ($identity === null) {
                //$this->handleFailure($response);
               $response->statusCode = 401;
                return [];
            }
            if(!\yii\helpers\ArrayHelper::isIn(\Yii::$app->controller->id,['my-listings','add-favorites','rem-favorite'])){
                /* @var $user api\modules\v1\models\User */
                \api\modules\v1\models\User::updateAll(['last_seen'=>time()], ['auth_key'=>$authHeader]);
            }
            return $identity;
        }
        $response->statusCode = 401;
        return [];
    }

//put your code here
}

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
class CustomKeyAuth extends \yii\filters\auth\AuthMethod {

    /**
     * @inheritdoc
     */
    public function authenticate($user, $request, $response) {
        $authHeader = $request->getHeaders()->get('apikey');
        if ($authHeader !== null) {
            $identity = $user->loginByAccessToken($authHeader, get_class($this));
            if ($identity === null) {
                //$response->statusCode = 401;
                $this->handleFailure($response);
                // var_dump($response);
                //return [];
            }
            return $identity;
        }
        else{
            //$response->statusCode = 401;
            $this->handleFailure($response);
        }
        //return [];
    }

//    public function handleFailure($response) {
//        $response->statusCode = 401;
//        $response->statusText = 'Your request was made with invalid credentials.';
//    }
//put your code here
}

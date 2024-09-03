<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace api\modules\v1\filters;

/**
 * Description of AdvertiserAccessRule
 *
 * @author Marian
 */
class ReviewsAccessRule extends \yii\filters\AccessRule{

    public $allow = true;  // Allow access if this rule matches
    public $roles = ['@']; // Ensure user is logged in.

    public function allows($action, $user, $request) {
        $parentRes = parent::allows($action, $user, $request);
        // $parentRes can be `null`, `false` or `true`.
        // True means the parent rule matched and allows access.
        if ($parentRes !== true) {
            return $parentRes;
        }
        return ($this->getUserId($request) == $user->id);
    }

    private function getUserId($request) {
        $id = $request->get('id');
        $review = \api\modules\v1\models\Reviews::findOne($id);
        return isset($review) ? $review->customer : null;
    }

}

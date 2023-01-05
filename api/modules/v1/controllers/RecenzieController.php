<?php

namespace api\modules\v1\controllers;
use yii\data\ActiveDataProvider;

class RecenzieController extends BasicApiController {

    
    public $modelClass = 'api\modules\v1\models\Recenzii';
    
//    protected function disableCheckAccessActions() {
//        return ['create'];
//    }

//    public function behaviors() {
//        $behaviors=parent::behaviors();
//        $behaviors['reviewAccess'] = [
//            'class' => AccessControl::className(),
//            'only' => ['update', 'view'],
//            'rules' => [['actions' => ['update', 'view']]],
//            'ruleConfig' => ['class' => '\api\modules\v1\filters\ReviewsAccessRule'],
//        ];
//        return $behaviors;
//    }
    
    public function actions() {
        $actions=parent::actions();
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        return $actions;
    }
    
     public function prepareDataProvider() {
         return $dataProvider = new ActiveDataProvider([
            'query' => \api\modules\v1\models\Recenzii::find()->where(['client'=>  \Yii::$app->user->id]),//->andWhere(['=', new Expression('date(FROM_UNIXTIME(created_at))'), new Expression('date(NOW())')]),
             'sort' => ['defaultOrder' => ['created_at' => SORT_DESC]]
        ]);
    }
    
}

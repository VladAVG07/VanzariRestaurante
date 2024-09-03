<?php

namespace api\modules\v1\models;

use backend\models\Categorii as CA;

class Categorii extends CA {
  public function fields() {
        $fields = parent::fields();
        $fields['produse'] = [];
        return $fields;
    }
}

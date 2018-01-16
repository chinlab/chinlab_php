<?php
namespace app\common\components\rongyun;

use Yii;
use yii\base\Component;

class Rongyun extends Component {

    public $appKey = 'appKey';
    public $appSecret = 'appSecret';
    public $jsonPath = "jsonsource/";
    public $rongCloud = null;

    public function getInstance() {
        $this->rongCloud = new RongCloud($this->appKey,$this->appSecret);
        return $this;
    }

    public function getToken($userId, $userName, $userPhoto) {
        return $this->rongCloud->user()->getToken($userId, $userName, $userPhoto);
    }
}

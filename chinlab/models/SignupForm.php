<?php
namespace app\models;

use yii\base\Model;
use app\models\Admin;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $nickname;
    public $userphone;
    public $sys_type;
    public $orgId;
    public $status;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            [['username'], 'required'],
            ['username', 'unique', 'targetClass' => '\app\models\Admin', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['orgId','default', 'value' => '0'],
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        	['userphone', 'trim'],
        	['nickname', 'trim'],
        	[['sys_type'], 'default', 'value' => '1'],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {   
        if (!$this->validate()) {
            return null;
        }
        $user = new Admin();
        $user->username = $this->username;
        $user->nickname = $this->nickname;
        $user->userphone= $this->userphone;
        $user->email    = $this->email;
        $user->sys_type = $this->sys_type;
        $user->orgId    = $this->orgId;
        $user->status   = $this->status;
        if($user->orgId>0){
        	$user->sys_type = 3;
        }
        $user->setPassword($this->password);
        $user->generateAuthKey();
        return $user->save(false) ? $user : null;
    }
}

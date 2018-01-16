<?php

namespace app\modules\patient\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "tversion".
 *
 * @property integer $version_id
 * @property string $version_name
 * @property string $version_design
 * @property string $version_url
 * @property string $version_time
 * @property string $version_device
 */
class Version extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tversion';
    }
    
    public function behaviors()
    {
    	return [
    		   [
    			 'class' =>  \yii\behaviors\TimestampBehavior::className(),
    			 'attributes' => [
    					ActiveRecord::EVENT_BEFORE_INSERT => ['version_time'],
    					//ActiveRecord::EVENT_BEFORE_UPDATE => ['version_time'],
    			  ],
    		   	 'value' => new \yii\db\Expression('NOW()'),
    		], 
    	];
    }
    /** 
     * @inheritdoc 
     */ 
    public function rules() 
    { 
        return [
            [['version_design'], 'string'],
            [['version_time'], 'safe'],
            [['version_name'], 'string', 'max' => 128],
            [['version_url'], 'string', 'max' => 256],
            [['version_device'], 'string', 'max' => 50],
        ]; 
    } 

    /** 
     * @inheritdoc 
     */ 
    public function attributeLabels() 
    { 
        return [ 
            'version_id' => '主键',
            'version_name' => '版本名',
            'version_design' => '版本更新描述',
            'version_url' => '下载地址',
            'version_time' => '版本修改时间',
            'version_device' => 'android 安卓设备 ， ios 苹果设备',
        ]; 
    }
}

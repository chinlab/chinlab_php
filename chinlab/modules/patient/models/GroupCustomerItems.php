<?php
namespace app\modules\patient\models;

use Yii;

/**
 * This is the model class for table "group_customer_items".
 *
 * @property string $items_id
 * @property string $customer_id
 * @property string $items_name
 * @property string $items_price
 * @property integer $create_time
 * @property integer $update_time
 * @property integer $is_delete
 */
class GroupCustomerItems extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'group_customer_items';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['items_id', 'customer_id', 'items_name', 'items_price', 'create_time', 'update_time', 'is_delete'], 'required'],
            [['items_id', 'customer_id', 'create_time', 'update_time', 'is_delete'], 'integer'],
            [['items_name'], 'string', 'max' => 200],
            [['items_price'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'items_id' => '主键ID',
            'customer_id' => '客户id',
            'items_name' => '项目名称',
            'items_price' => '项目费用',
            'create_time' => '创建时间',
            'update_time' => '修改时间',
            'is_delete' => '1代表未删除，2已经删除',
        ];
    }


}

<?php
namespace app\modules\patient\behavior;

/**
 * Created by PhpStorm.
 * User: user
 * Date: 2016/12/21
 * Time: 11:32
 */
use yii\behaviors\AttributeBehavior;
use yii\db\BaseActiveRecord;
use yii\db\Expression;

class OrderStateUpdateBehavior extends AttributeBehavior
{
    /**
     * @var string the attribute that will receive timestamp value
     * Set this property to false if you do not want to record the creation time.
     */
    public $updateAtAttribute = 'process_record';

    /**
     * @inheritdoc
     *
     * In case, when the value is `null`, the result of the PHP function [time()](http://php.net/manual/en/function.time.php)
     * will be used as value.
     */
    public $value;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if (empty($this->attributes)) {
            $this->attributes = [
                BaseActiveRecord::EVENT_BEFORE_UPDATE => [$this->updateAtAttribute],
            ];
        }
    }

    /**
     * @inheritdoc
     *
     * In case, when the [[value]] is `null`, the result of the PHP function [time()](http://php.net/manual/en/function.time.php)
     * will be used as value.
     */
    protected function getValue($event)
    {
        if ($this->value === null) {
            $json = OrderJson::getValue($this->updateAtAttribute);
            return new Expression('json_merge(\'' . $json . '\', ' . $this->updateAtAttribute . ')');
        }
        return parent::getValue($event);
    }
}

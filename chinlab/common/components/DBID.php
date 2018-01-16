<?php
namespace app\common\components;
use Yii;
use yii\log\Logger;
use yii\base\Component;

class DBID extends Component
{

    /**
     * 获取唯一Id
     *
     * @param string $componentsKeyTable componentsKey.table
     *
     * @return string
     * @throws \CException
     */
    public function getID($componentsKeyTable)
    {

        $componentsKey = substr($componentsKeyTable, 0, strpos($componentsKeyTable, "."));
        $tableName = substr($componentsKeyTable, strpos($componentsKeyTable, ".") + 1);

        //check componentsKey isset
        if (!isset(Yii::$app->{$componentsKey})) {
            Yii::getLogger()->log($componentsKey . '.' . $tableName . " yii componentKey not found", Logger::LEVEL_ERROR, "error.getOneId");
            throw new \Exception($componentsKey . '.' . $tableName . " yii componentKey not found");
        }

        //check table length
        if (strlen($tableName) > 200) {
            Yii::getLogger()->log($componentsKey . '.' . $tableName . " is too long", Logger::LEVEL_ERROR, "error.getOneId");
            throw new \Exception($componentsKey . '.' . $tableName . " is too long");
        }
        $redis = Yii::$app->redis;
        $redisKey = AppRedisKeyMap::getIdKey($componentsKeyTable);
        if (!$redis->exists($redisKey)) {

            $redisCheckKey = $redisKey . ".check";
            if ($redis->incr($redisCheckKey) > 1) {
                sleep(2);
                $this->getID($componentsKeyTable);
            } else {
                $redis->expire($redisCheckKey, 5);
            }
            if (!$this->checkDbTableIsExists($tableName, $componentsKey)) {
                throw new \Exception($componentsKey . '.' . $tableName . " is not exists");
            }
            if (!$this->createIdTables()) {
                throw new \Exception('create id tables failed');
            }
            $mysqlNum = $this->getIdTablesNum($tableName, $componentsKey);
            if (!$mysqlNum) {
                throw new \Exception('mysql id get failed');
            }
            $recordNum = strval($mysqlNum) . "0000";
            $redis = Yii::$app->redis;
            $redis->set($redisKey, $recordNum);
            $redis->del($redisCheckKey);
        }
        $id  = $redis->incr($redisKey);
        if (substr($id, -4) == "0000") {
            self::recordNum(substr($id, 0, -4), $tableName, $componentsKey);
        }
        return $id;
    }

    /**
     * 每一万次记录到数据库
     *
     * @param $id
     * @param $tableName
     * @param $componentsKey
     *
     * @return boolean
     */
    private function recordNum($id, $tableName, $componentsKey) {
        try {

            $res = yii::$app->{$componentsKey}->createCommand("SELECT database() AS nowDatabase")->queryOne();
            $database = $res['nowDatabase'];
            $primaryKey = $database . '_' . $tableName;

            Yii::$app->db->createCommand()->update('db_code_record', ['record_num' => $id], 'db_table_name=:id AND record_num < :recordNum', [':id' => $primaryKey, ':recordNum' => $id])->execute();
            return true;
        } catch (\Exception $e) {
            Yii::getLogger()->log($componentsKey . '.' . $tableName . " record number failed AND num is [" .$id. "]", Logger::LEVEL_ERROR, "error.recordOneId");
            return false;
        }
    }

    /**
     * 获取数据记录值
     *
     * @param $tableName
     * @param $componentsKey
     *
     * @return string
     */
    private function getIdTablesNum($tableName, $componentsKey)
    {

        try {

            $res = yii::$app->{$componentsKey}->createCommand("SELECT database() AS nowDatabase")->queryOne();
            $database = $res['nowDatabase'];
            $primaryKey = $database . '_' . $tableName;
            $sql = "SELECT * FROM db_code_record WHERE db_table_name = '{$primaryKey}'";
            $res = Yii::$app->db->createCommand($sql)->queryOne();
            $number = '1000000000';
            if ($res) {
                $number = $res['record_num'] + 1;
                Yii::$app->db->createCommand()->update('db_code_record', ['record_num' => $number], 'db_table_name=:id', [':id' => $primaryKey])->execute();
            } else {
                Yii::$app->db->createCommand()->insert('db_code_record', ['record_num' => $number, 'db_table_name' => $primaryKey])->execute();
            }

            return $number;
        } catch (\Exception $e) {
            Yii::getLogger()->log($componentsKey . '.' . $tableName . " record number failed", Logger::LEVEL_ERROR, "error.getOneId");
            return false;
        }
    }

    /**
     * 获取该表是否在数据库存在，排除复合主键
     *
     * @param $tableName
     * @param $componentsKey
     *
     * @return boolean
     */
    private function checkDbTableIsExists($tableName, $componentsKey)
    {
        try {
            $sql = "DESC " . $tableName;
            $res = yii::$app->{$componentsKey}->createCommand($sql)->queryAll();
            if (!$res) {
                Yii::getLogger()->log($componentsKey . '.' . $tableName . " is not exists", Logger::LEVEL_ERROR, "error.getOneId");
                return false;
            }
            $countPrimaryKey = 0;
            foreach ($res as $k => $v) {
                if (strtoupper($v['Key']) == "PRI") {
                    $countPrimaryKey++;
                }
            }
            if ($countPrimaryKey > 1) {
                Yii::getLogger()->log($componentsKey . '.' . $tableName . " is not support multi primary", Logger::LEVEL_ERROR, "error.getOneId");
                return false;
            }
            return true;
        } catch (\Exception $e) {
            Yii::getLogger()->log($componentsKey . '.' . $tableName . " is not exists", Logger::LEVEL_ERROR, "error.getOneId");
            return false;
        }
    }

    /**
     * 检查table数据库是否存在
     *
     * @return bool
     */
    private function createIdTables()
    {
        try {

            $sql = "SELECT table_name FROM information_schema.TABLES WHERE table_name ='db_code_record'";
            $result = Yii::$app->db->createCommand($sql)->queryOne();
            if ($result) {
                return true;
            }
            $sql = <<<EOT
CREATE TABLE `db_code_record` (
  `db_table_name` char(250) NOT NULL,
  `record_num` char(50) NOT NULL,
  PRIMARY KEY (`db_table_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
EOT;
            Yii::$app->db->createCommand($sql)->query();
            return true;
        } catch (\Exception $e) {
            Yii::getLogger()->log("create db_code_record failed", Logger::LEVEL_ERROR, "error.getOneId");
            return false;
        }
    }
}
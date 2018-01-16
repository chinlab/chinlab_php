<?php 
namespace app\models;
use yii\base\Exception;
use yii\log\Logger;
use yii\sphinx\Query;
use yii\sphinx\MatchExpression;
use Yii;

class Search {
	
	//医院搜索
	public function GetHospitalByCondition($select, $condition, $order, $page = 1, $limit = 10) {
	
		if (!$condition) {
			return [];
		}
		try {
			$object = (new Query())->select($select)->where($condition['condition'])->from('hospital_search');
			$matchFlag = false;
			if (isset($condition['match']) && $condition['match']) {
				$i = 0;
				$express = new MatchExpression();
				foreach($condition['match'] as $k => $v ) {
					if ($k == "expression") {
						$tmp = $v;
					} else {
						$tmp = [$k => Yii::$app->sphinx->escapeMatchValue($v)];
					}
					if ($i == 0) {
						$express->match($tmp);
					} else {
						$express->andMatch($tmp);
					}
					$i++;
				}
				$object = $object->match($express);
				$matchFlag = true;
			}
			$data = ['data'=>[],'count'=>0];
			$count = (new Query())->select("count(*)")->where($condition['condition'])->from('hospital_search');
			if($matchFlag){
				$count->match($express);
			}
			$count = $count->createCommand()->queryScalar();
			$sql = $object->createCommand()->getRawSql();
		    $sql .=  ' ORDER BY '.$order.' LIMIT '.($page - 1) * $limit.' ,'.$limit.' OPTION max_matches = '.$page * $limit;
		    //echo $sql;die;
		    $result = Yii::$app->sphinx->createCommand($sql)->queryAll();
			if($result){
				$data['data'] = $result;
				$data['count'] = $count;
			}
			return $data;
		} catch (Exception $e) {
			Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
			return [];
		}
	}
	

	//根据医院ID和科室ID搜索医生
	public function GetDoctorSum($condition,$group='') {
		if (!$condition) {
			return 0;
		}
		try {
		    $object = (new Query())->select('count(*)')->from('doctor_search');
		    if($group){
		    	$object->groupBy($group);
		    }
		    if($condition['condition']){
		    	$object->where($condition['condition']);
		    }
		    if (isset($condition['match']) && $condition['match']) {
		    	$i = 0;
		    	$express = new MatchExpression();
		    	foreach($condition['match'] as $k => $v ) {
		    		if ($k == "expression") {
		    			$tmp = $v;
		    		} else {
		    			$tmp = [$k => Yii::$app->sphinx->escapeMatchValue($v)];
		    		}
		    		if ($i == 0) {
		    			$express->match($tmp);
		    		} else {
		    			$express->andMatch($tmp);
		    		}
		    		$i++;
		    	}
		    	$object = $object->match($express);
		    }
		    //echo $object->createCommand()->getRawSql();die;
			return  $object->createCommand()->queryScalar();
		} catch (Exception $e) {
			Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
			return 0;
		}
	}
	//根据医院ID搜索
	public function GetHospitalByConditionUseWithIn($hospital) {
	
		if (!$hospital) {
			return [];
		}
		try {
			$sql = 'SELECT `hospital_id`, `hospital_name`, `district_id`,`district_address`, `hospital_level`, `sections_num`, `doctors_num`, `is_search` FROM `hospital_search` WHERE  is_delete > 0  AND hospital_id in('.$hospital.')  ORDER BY  hospital_id asc ';
			$result = Yii::$app->sphinx->createCommand($sql)->queryAll();
			return $result;
		} catch (Exception $e) {
			Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
			return [];
		}
	}
	
	//医院科室搜索
	public function GetHospitalSectionByCondition($select, $condition, $order, $page = 1, $limit = 10,$group='hospital_id',$count='count(*)') {
	
		if (!$condition) {
			return [];
		}
		try {
			$object = (new Query())->select($select)->from('hospital_section_search');
			if($group){
				$object->groupBy($group);
			}
			if($condition['condition']){
				$object->where($condition['condition']);
			}
			$matchFlag = false;
			if (isset($condition['match']) && $condition['match']) {
				$i = 0;
				$express = new MatchExpression();
				foreach($condition['match'] as $k => $v ) {
					if ($k == "expression") {
						$tmp = $v;
					} else {
						$tmp = [$k => Yii::$app->sphinx->escapeMatchValue($v)];
					}
					if ($i == 0) {
						$express->match($tmp);
					} else {
						$express->andMatch($tmp);
					}
					$i++;
				}
				$object = $object->match($express);
				$matchFlag = true;
			}
			$data = ['data'=>[],'count'=>0];
			$count = (new Query())->select($count)->from('hospital_section_search');
			if($matchFlag){
				$count->match($express);
			}
			if($condition['condition']){
				$count->where($condition['condition']);
			}
			//echo $count->createCommand()->getRawSql();die;
			$count = $count->createCommand()->queryScalar();
			$sql = $object->createCommand()->getRawSql();
			$sql .=  ' ORDER BY '.$order.' LIMIT '.($page - 1) * $limit.' ,'.$limit.' OPTION max_matches = '.$page * $limit;
			//echo $sql;die;
			$result = Yii::$app->sphinx->createCommand($sql)->queryAll();
			if($result){
				$data['data'] = $result;
				$data['count'] = $count;
			}
			return $data;
		} catch (Exception $e) {
			//var_dump($e->getMessage());
			Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
			return [];
		}
	}
	
	

	//疾病搜索
	public function GetHospitaldiseaseByCondition($select, $condition, $order, $page = 1, $limit = 10,$group='hospital_id') {
	
		if (!$condition) {
			return [];
		}
		try {
			$object = (new Query())->select($select)->from('doctor_search');
			if($group){
				$object->groupBy($group);
			}
	        if($condition['condition']){
	        	$object->where($condition['condition']);
	        }
			$matchFlag = false;
			if (isset($condition['match']) && $condition['match']) {
				$i = 0;
				$express = new MatchExpression();
				foreach($condition['match'] as $k => $v ) {
					if ($k == "expression") {
						$tmp = $v;
					} else {
						$tmp = [$k => Yii::$app->sphinx->escapeMatchValue($v)];
					}
					if ($i == 0) {
						$express->match($tmp);
					} else {
						$express->andMatch($tmp);
					}
					$i++;
				}
				$object = $object->match($express);
				$matchFlag = true;
			}
			$data = ['data'=>[],'count'=>0];
			$count = (new Query())->select("count(*)")->from('doctor_search');
			if($group){
				$count->groupBy($group);
			}
			if($condition['condition']){
				$count->where($condition['condition']);
			}
			if($matchFlag){
				$count->match($express);
			}
			$count = $count->createCommand()->queryScalar();
			$sql  = $object->createCommand()->getRawSql();
			if(!isset($condition['match']['sdoctor_name'])){
				$sql .=  ' ORDER BY '.$order.' LIMIT '.($page - 1) * $limit.' ,'.$limit.' OPTION max_matches = '.$page * $limit;
			}
			//echo $sql;die;
			$result = Yii::$app->sphinx->createCommand($sql)->queryAll();
			if($result){
				$data['data']  = $result;
				$data['count'] = $count;
			}
			return $data;
		} catch (Exception $e) {
			//var_dump($e->getMessage());
			Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
			return [];
		}
	}
	
	public function getSectionInfo($select,$condition)
	{

		if (!$condition) {
			return [];
		}
		try {
			$object = (new Query())->select($select)->from('hospital_section_search');
			if($condition['condition']){
				$object->where($condition['condition']);
			}
			if (isset($condition['match']) && $condition['match']) {
				$i = 0;
				$express = new MatchExpression();
				foreach($condition['match'] as $k => $v ) {
					if ($k == "expression") {
						$tmp = $v;
					} else {
						$tmp = [$k => Yii::$app->sphinx->escapeMatchValue($v)];
					}
					if ($i == 0) {
						$express->match($tmp);
					} else {
						$express->andMatch($tmp);
					}
					$i++;
				}
				$object = $object->match($express);
			}
			$sql  = $object->createCommand()->getRawSql();
			//echo $sql;die;
			return  Yii::$app->sphinx->createCommand($sql)->queryOne();
		} catch (Exception $e) {
			//var_dump($e->getMessage());
			Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
			return [];
		}
	}
	
	public function getDoctorInfo($select,$doctorId)
	{
	
		if (!$doctorId) {
			return [];
		}
		try {
			$express = new MatchExpression();
			$express->match(['sdoctor_id' => Yii::$app->sphinx->escapeMatchValue($doctorId)]);
			$object = (new Query())->select($select)->from('doctor_search');
			$object = $object->match($express);
			$sql  = $object->createCommand()->getRawSql();
			//echo $sql;die;
			return  Yii::$app->sphinx->createCommand($sql)->queryOne();
		} catch (Exception $e) {
			//var_dump($e->getMessage());
			Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
			return [];
		}
	}
	
}
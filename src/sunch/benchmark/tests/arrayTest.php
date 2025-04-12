<?php

namespace sunch\benchmark\tests;

use pocketmine\utils\UUID;
use function array_merge;
use function spl_object_hash;

class arrayTest extends Test{

	public function run(){
		$array = [];
		for($i = 0; $i < 200; $i++){ //数组创建
			for($j = 0; $j < 200; $j++){
				$uuid = UUID::fromRandom();
				$array['key_'.$i]['key_'.$j] = $uuid;
			}
		}

		$newArray = [];
		for($i = 0; $i < 4000; $i++){ //数组创建 / 随机访问 / 合并
			$index = mt_rand(1, 199);
			$j = mt_rand(1, 199);
			$uuid = $array['key_'.$index]['key_'.$j];
			$newArray[spl_object_hash($uuid)] = $uuid;
			$merge = array_merge($array, $newArray);
		}

		return $merge;
	}

	public function getName(){
		return "PHP数组操作";
	}

	public function clacScore($timeMs){
		return round(3 / $timeMs * 1000000, 2);
	}
}
<?php

namespace sunch\benchmark\tests;

use pocketmine\math\AxisAlignedBB;
use pocketmine\utils\Random;
use function mt_rand;
use function round;

class aabbTest extends Test {

	/** @var AxisAlignedBB[]  */
	public $aabbs = [];

	public function __construct() {
		$random = new Random();
		for($i = 0; $i < 5000; $i++) {
			$x = $i - $random->nextFloat() * 5;
			$y = $i - $random->nextFloat() * 5;
			$z = $i - $random->nextFloat() * 5;
			$this->aabbs[$i] = new AxisAlignedBB($x, $y, $z, $x + $random->nextFloat() * 10, $y  + $random->nextFloat() * 10, $z + $random->nextFloat() * 10);
		}
	}

	public function run() {
		$num = count($this->aabbs);
		foreach($this->aabbs as $aabb) {
			for($i = 0; $i < $num; $i++) {
				if($aabb->intersectsWith($this->aabbs[$i])){
					$aabb->calculateXOffset($this->aabbs[$i], mt_rand(-15, 15) / 10);
					$aabb->calculateYOffset($this->aabbs[$i], mt_rand(-15, 15) / 10);
					$aabb->calculateZOffset($this->aabbs[$i], mt_rand(-15, 15) / 10);
				}
			}
		}
	}

	public function getName() {
		return "碰撞箱性能";
	}

	public function clacScore($timeMs) {
		return round(45 / $timeMs * 100000, 2);
	}
}
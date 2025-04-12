<?php

namespace sunch\benchmark\tests;

use function deg2rad;

class mathTest extends Test {

	public function run() {
		$max = 1000000; //100w以内质数运算
		$sieve = array_fill(1, $max, true);
		for ($i = 1; $i <= $max; $i++) {
			if($sieve[$i]){
				for($j = pow($i, 2); $j < $max; $j += $i) {
					$sieve[$j] = false;
				}
			}
		}

		$a = 0;
		for($i = 0; $i < 30000000; $i++){ //3000w浮点
			$a += sqrt($i) * tan(deg2rad($i % 180));
		}

		return $a;
	}

	public function getName() {
		return 'PHP数学运算';
	}

	public function clacScore($timeMs) {
		return round(83 / $timeMs * 100000, 2);
	}
}
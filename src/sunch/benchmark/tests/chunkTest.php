<?php

namespace sunch\benchmark\tests;

use pocketmine\level\format\anvil\Chunk;
use function mt_rand;

class chunkTest extends Test {

	private $chunk;

	public function __construct() {
		$chunk = Chunk::getEmptyChunk(0, 0);
		for ($x = 0; $x < 16; $x++) {
			for ($y = 0; $y < 128; $y++) {
				for ($z = 0; $z < 16; $z++) {
					$chunk->setBlock($x, $y, $z, mt_rand(0, 255), mt_rand(0, 255));
					$chunk->setBlockLight($x, $y, $z, mt_rand(0, 15));
				}
			}
		}
		$chunk->populateSkyLight();
		$this->chunk = $chunk;
	}

	public function run() {
		$chunk = $this->chunk;
		for ($i = 0; $i < 500; $i++) {
			$binary = $chunk->toBinary();
			$fastBinary = $chunk->toFastBinary();
		}

		for ($i = 0; $i < 500; $i++) {
			Chunk::fromBinary($binary);
			Chunk::fromFastBinary($fastBinary);
		}
	}

	public function getName() {
		return '区块速度测试 Anvil';
	}

	public function clacScore($timeMs) {
		return round(106 / $timeMs * 10000, 2);
	}
}
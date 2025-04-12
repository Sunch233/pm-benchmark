<?php

namespace sunch\benchmark\tests;

use function function_exists;
use function str_repeat;
use function zlib_decode;
use function zlib_encode;
use const ZLIB_ENCODING_DEFLATE;

class zlibTest extends Test {

	public $data = '';

	public function __construct() {
		$this->data .= $this->randomkeys(64 * 1024);
		$this->data .= str_repeat($this->randomkeys(1), 160 * 1024);
		$this->data .= str_repeat($this->randomkeys(1), 96 * 1024);
		$this->data .= $this->randomkeys(48 * 1024); //模拟区块包
	}

	public function randomkeys($length) {
		$pattern = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLOMNOPQRSTUVWXYZ=_@[].,<>/';
		$key = '';
		for ($i = 0; $i < $length; $i++) {
			$key .= $pattern[mt_rand(0, strlen($pattern) - 1)];
		}
		return $key;
	}

	public function run() {
		$useExt = function_exists('libdeflate_zlib_compress');
		for ($i = 0; $i < 500; $i++) {
			$encoded = $useExt ? libdeflate_zlib_compress($this->data, 7) : zlib_encode($this->data, ZLIB_ENCODING_DEFLATE, 7);
		}
		for ($i = 0; $i < 500; $i++) {
			$decoded = zlib_decode($encoded);
		}
		return $decoded;
	}

	public function getName() {
		return "网络数据包压缩/解压";
	}

	public function clacScore($timeMs) {
		return round(201 / $timeMs * 10000, 2);
	}
}
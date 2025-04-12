<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
 */

namespace sunch\benchmark;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use sunch\benchmark\tests\aabbTest;
use sunch\benchmark\tests\arrayTest;
use sunch\benchmark\tests\chunkTest;
use sunch\benchmark\tests\mathTest;
use sunch\benchmark\tests\Test;
use sunch\benchmark\tests\zlibTest;
use function microtime;
use const PHP_INT_SIZE;
use const PHP_OS;
use const PHP_VERSION;

class Main extends PluginBase{

	/** @var Test[] */
	private $tests = [];

	public function onEnable(){
		$this->tests = [
			new mathTest(),
			new arrayTest(),
			new chunkTest(),
			new zlibTest(),
			new aabbTest()
		];
		$this->getLogger()->warning("----PM跑分王----");
		$this->getLogger()->warning('不同的运行环境，PHP版本，插件，服务端都会对结果造成影响');
	}

	public function onCommand(CommandSender $sender, Command $command, $label, array $args){
		if($command->getName() == 'bm'){
			if($sender instanceof Player){
				$sender->sendMessage(TextFormat::RED . '请在控制台运行此命令');
			}else{
				$this->benchmark();
			}
			return true;
		}
		return false;
	}

	public function benchmark() {
		$logger = $this->getLogger();
		$server = $this->getServer();

		$logger->info("服务端: ".$server->getName()." ".$server->getPocketMineVersion()." (API: ".$server->getApiVersion().")");
		$logger->info("PHP ".PHP_VERSION." ".(PHP_INT_SIZE * 8)."bit (OS: ".PHP_OS.")");
		$logger->notice('开始进行基准测试...');
		$startTime = microtime(true);
		$totalScore = 0;
		foreach($this->tests as $test){
			$logger->info("开始运行测试 ".$test->getName());
			$time = microtime(true);
			$test->run();
			$timeMs = round((microtime(true) - $time) * 1000);
			$score = $test->clacScore($timeMs);
			$totalScore += $score;
			$logger->info("运行完毕，耗时: $timeMs ms, 分数 $score");
		}
		$totalTime = round((microtime(true) - $startTime), 2);
		$logger->notice("基准测试结束, 耗时$totalTime s, 得分$totalScore");
	}
}

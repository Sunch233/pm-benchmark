<?php

namespace sunch\benchmark\tests;

abstract class Test {
	abstract public function run();

	abstract public function getName();

	abstract public function clacScore($timeMs);
}
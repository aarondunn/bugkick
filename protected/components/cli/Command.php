<?php
/**
 * Command class
 *
 * @author f0t0n
 */
class Command extends CConsoleCommand {

	public function __construct($name, $runner) {
		ini_set('memory_limit', '512M');
		ini_set('max_execution_time', '0');
		parent::__construct($name, $runner);
	}
}
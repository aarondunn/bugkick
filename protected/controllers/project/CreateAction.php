<?php
/**
 * CreateAction
 *
 * @author f0t0n
 */
class CreateAction extends Action {
	
	public function run() {
		$this->controller->forward('project/edit');
	}
}
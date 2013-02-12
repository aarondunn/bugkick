<?php
/**
 * Author: Alexey kavshirko@gmail.com
 * Date: 14.02.12
 * Time: 12:37
 */
class DatabasePlanConfig extends PlanConfig {

	public function __construct($planName) {
		parent::__construct($planName);
		$this->init();
	}

	protected function init() {
        parent::init();
		//	Initialize object properties using the data from database.
	}

    protected function validatePlanName() {

    }

    protected function getPlanConfigData() {

    }

	public function getMaxProjectsCount() {

    }

	public function getPlanID() {

    }

    public function getIsGithubIntegrationAvailable() {

    }
}
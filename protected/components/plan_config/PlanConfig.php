<?php
/**
 * Author: Alexey kavshirko@gmail.com
 * Date: 14.02.12
 * Time: 12:36
 */
abstract class PlanConfig {
	protected $planName;

	protected $planID = false;
	protected $maxProjectsCount = false;
    protected $isGithubIntegrationAvailable = false;

	public function __construct($planName) {
		$this->planName = $planName;
	}

	protected function init() {
        $this->validatePlanName();
    }
    
    protected abstract function validatePlanName();
    protected abstract function getPlanConfigData();

    public abstract function getPlanID();
	public abstract function getMaxProjectsCount();
    public abstract function getIsGithubIntegrationAvailable();
}

<?php
/**
 * Author: Alexey kavshirko@gmail.com
 * Date: 14.02.12
 * Time: 12:37
 */
class FilePlanConfig extends PlanConfig {
	public function __construct($planName) {
		parent::__construct($planName);
		$this->init();
	}

	protected function init() {
        parent::init();
        $config = $this->getPlanConfigData();
		//	Initialize object properties using the data from config file.
        $this->planID = $config['id'];
        $this->maxProjectsCount = $config['projects_available'];
        $this->isGithubIntegrationAvailable =
                $config['is_github_integration_available'];
	}

    protected function validatePlanName() {
        if(!isset(Yii::app()->params['plans'][$this->planName])) {
            throw new CException(
                    "Payment plan '{$this->planName}' is absent.");
        }
    }

    protected function getPlanConfigData() {
        return Yii::app()->params['plans'][$this->planName];
    }

	public function getMaxProjectsCount() {
       return $this->maxProjectsCount;
	}

	public function getPlanID() {
        return $this->planID;
	}

    public function getIsGithubIntegrationAvailable() {
        return $this->isGithubIntegrationAvailable;
    }
}

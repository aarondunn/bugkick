<?php
/**
 * Author: Alexey kavshirko@gmail.com
 * Date: 14.02.12
 * Time: 12:35
 */
class StripePlanConfigFactory implements PlanConfigFactory
{
	/**
	 * @return PlanConfig
	 */
	public static function createPlanConfig($planName, $storageType)
    {
		$className = ucfirst($storageType) . 'PlanConfig';
		if(class_exists($className)) {
			return new $className($planName);
		}
		throw new PlanConfigFactoryException(
			"Storage type '{$storageType}' is not supported yet.");
	}
}

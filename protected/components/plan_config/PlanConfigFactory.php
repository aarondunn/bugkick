<?php
/**
 * Author: Alexey kavshirko@gmail.com
 * Date: 14.02.12
 * Time: 12:34
 */
interface PlanConfigFactory
{
	public static function createPlanConfig($planName, $storageType);
}

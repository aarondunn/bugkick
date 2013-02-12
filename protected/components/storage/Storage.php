<?php
/**
 * Author: Alexey kavshirko@gmail.com
 * Date: 24.05.12
 * Time: 0:12
 */
class Storage{

    const STORAGE_TYPE_SUFFIX = 'Storage';

    /**
     *
     * @param string $storageType. When not specified,
     * storage that defined in the main config will be returned.
     * @return BaseStorage storage instance
     */
    public static function get($storageType=null)
    {
        $storageType = (empty($storageType))? Yii::app()->params['storageType'] : $storageType;
        $componentName = strtolower($storageType) . self::STORAGE_TYPE_SUFFIX;
        if (Yii::app()->hasComponent($componentName)
            && Yii::app()->{$componentName} instanceof BaseStorage
        ) {
            return Yii::app()->{$componentName};
        }
        return null;
    }
}
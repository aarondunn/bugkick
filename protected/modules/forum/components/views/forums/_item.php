<?php
/**
 * Author: Alexey kavshirko@gmail.com
 * Date: 21.11.12
 * Time: 23:12
 */
echo CHtml::tag('li', array(),
    CHtml::link(
        CHtml::encode(BKHelper::truncateString($data->title)),
        Yii::app()->createUrl('/forum/forum/view', array('id'=>$data->id))
    )
    //. CHtml::tag('span', array('class'=>'topics-count'), $data->topicsCount . ' topics')
    . CHtml::tag('span', array('class'=>'topics-count'), $data->topicsCount)
);

<?php
$this->breadcrumbs = array(
    'Bugs' => array('index'),
    'Create',
);

$this->menu = array(
    array('label' => 'List Bug', 'url' => array('index')),
    array('label' => 'Manage Bug', 'url' => array('admin')),
);
?>

<h1>Create Bug</h1>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>
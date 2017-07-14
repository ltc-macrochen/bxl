<?php
/* @var $this SiteController */
/* @var $error array */

$this->pageTitle=Yii::app()->name . ' - Error';
$this->breadcrumbs=array(
	'Error',
);
?>

<body class="gray-bg"

<div style="padding:20px;">
    <h2 style="font-size:1.6em;margin-bottom:0.75em;font-weight:normal;color:#111;">出错了 <?php echo $code; ?></h2>

    <div class="error">
    <?php echo CHtml::encode($message); ?>
    </div>
</div>
      
</body>
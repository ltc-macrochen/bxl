<?php
/* @var $this CmsPrivilegeController */
/* @var $data CmsPrivilege */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id),array('view','id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('adminId')); ?>:</b>
	<?php echo CHtml::encode($data->adminId); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('siteId')); ?>:</b>
	<?php echo CHtml::encode($data->siteId); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('catId')); ?>:</b>
	<?php echo CHtml::encode($data->catId); ?>
	<br />


</div>
<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/custom/jquery-ui-1.10.4.custom.css" />
	<?php
		Yii::app()->getClientScript()->registerCoreScript('jquery');
		//Yii::app()->getClientScript()->registerCoreScript('jquery-ui');
	?>

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>
	<script src="js/jquery-ui-1.10.4.custom.js"></script>

<div class="container" id="page">

	<div id="header" style="position: relative;">
		<div style="position:relative; top:5px; left:40%;">
			<?php $this->widget('RW_GoalsComplete', ['current_project'=>true]); ?>
		</div>
		<div id="logo" style="position:absolute; top:0px;" class="span-12">
		<?php echo CHtml::encode(Yii::app()->name)." - "; $this->widget('PW_ProjectHeader'); ?></div>
		<div style="position: absolute; top: 10px; right:10px;">
			<?php
			$this->widget('RW_ReportsPreview');
			echo CHtml::beginForm(array('ticket/QuickSearch'), 'GET', array('style'=>'display:inline;'));
			echo CHtml::textField('text');
			echo CHtml::tag('span', [
				'class'=>'ui-icon ui-icon-search',
				'style'=>'display:inline-block'
			]).CHtml::tag('/span');
			echo CHtml::linkButton('Поиск');
			echo CHtml::endForm();
			echo CHtml::tag('span', [
				'class'=>'ui-icon ui-icon-plus',
				'style'=>'display:inline-block'
			]).CHtml::tag('/span');
			echo CHtml::link('Добавить', ['ticket/create']);
			?>
		</div><br/>
		<?php  ?>
	</div><!-- header -->

	<div id="mainmenu">
		<span id="rightmenu" class="right top">
			<?php $this->widget('zii.widgets.CMenu',array(
				'items'=>array(
					array('label'=>'Пользователи', 'url'=>array('/user/index'), 'visible'=>User::CheckLevel(30)),
					array('label'=>'Проекты', 'url'=>array('/project/index'), 'visible'=>User::CheckLevel(20)),
					array('label'=>'О системе', 'url'=>array('/site/page', 'view'=>'about')),
					array('label'=>'Мой профиль', 'url'=>array('/user/ViewProfile')),
					array('label'=>'Логин', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
					array('label'=>'Выход ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest),
				),
			)); ?>
		</span>
		<span id="leftmenu" class="">
			<?php $this->widget('zii.widgets.CMenu',array(
				'items'=>array(
					array('label'=>'Главная', 'url'=>array('/site/index')),
					array('label'=>'БКО', 'url'=>array('/iteration/bau')),
					array('label'=>'Итерация', 'url'=>array('/iteration/index')),
					array('label'=>'Бэклог', 'url'=>array('/ticket/plan')),
					array('label'=>'Поиск задач', 'url'=>array('/ticket/admin')),
					array('label'=>'Отчеты', 'url'=>array('/site/page', 'view'=>'report')),
				),
			)); ?>
		</span>
	</div><!-- mainmenu -->
	<?php if(isset($this->breadcrumbs)):?>
		<?php $this->widget('zii.widgets.CBreadcrumbs', array(
			'homeLink'=>CHtml::link('Домой', array('/')),
			'links'=>$this->breadcrumbs,
		)); ?><!-- breadcrumbs -->
	<?php endif?>

	<?php echo $content; ?>

	<div class="clear"></div>
	<?php $this->widget("TW_TaskViewExtender"); ?>
	<div id="footer">
		Developed by <a href="http://wkfteam.ru/">WKF Team</a>.<br/>
		Available on <a href="https://github.com/wkf-team/tasker">GitHub</a> under MIT license<br/>
		<?php echo Yii::powered(); ?>
	</div><!-- footer -->

</div><!-- page -->

</body>
</html>

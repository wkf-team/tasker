<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>
<div class="span-auto"><!-- span-19 -->
	<div id="content">
		<?php echo $content; ?>
	</div><!-- content -->
</div>
<div class="span-side last"><!-- span-5 -->
	<div id="sidebar">
	<?php
		$this->beginWidget('zii.widgets.CPortlet', array(
			'title'=>'Действия',
		));
		$this->widget('zii.widgets.CMenu', array(
			'items'=>$this->menu,
			'htmlOptions'=>array('class'=>'operations'),
		));
		$this->endWidget();
	?>
	<?php
	if (isset($this->historyItemsProvider)) {
		echo "<h3>История</h3>";
		$this->widget('zii.widgets.CListView', [
			'dataProvider'=>$this->historyItemsProvider,
			'itemView'=>$this->historyItemsView,
		]);
	}
	?>
	</div><!-- sidebar -->
</div>
<?php $this->endContent(); ?>
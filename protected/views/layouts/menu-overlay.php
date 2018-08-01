<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>
<style>
#menu-overlay {
	position: fixed;
	width: 200px;
	right: 50%;
	top: 150px;
	padding: 0.4em;
	z-index:20;
}
#menu-overlay h3 {
	margin: 0;
	padding: 0.4em;
	text-align: center;
}
</style>
<div id="content">
	<?php echo $content; ?>
</div><!-- content -->

<div class="toggler">
  <div id="menu-overlay" class="ui-widget-content ui-corner-all">
    <h3 class="ui-widget-header ui-corner-all">Меню <a href="" onclick="return ShowHideMenu();">X</a></h3>
    <p>
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
    </p>
  </div>
</div>
<?php $this->endContent(); ?>

<script>

$(function () {
	$( "#menu-overlay" ).hide();
});

function ShowHideMenu() { 
    $( "#menu-overlay" ).toggle( "drop", {}, 500 );
    return false;
};
</script>
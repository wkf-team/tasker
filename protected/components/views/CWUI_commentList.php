<?php
echo CHtml::link("Add new comment", array('comment/create', 'ticket_id'=>$ticket_id), array('id'=>'btnAddComment'));
if ($dataProvider->totalItemCount > 0) {
$this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'application.views.comment._view',
));
}
?>

<script>
$(function () {
	$("#btnAddComment").button({
      icons: {
        primary: "ui-icon-plusthick"
      },
      text: false
    });
});
</script>
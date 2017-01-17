<style type="text/css">
.ui-icon-red {
	background-image: url(css/custom/images/ui-icons_cd0a0a_256x240.png)!important;
}
.ui-icon-black {
	background-image: url(css/custom/images/ui-icons_202020_256x240.png)!important;
}
</style>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/custom/jquery-ui-1.10.4.custom.css" />
<span class="ui-icon <?
			if ($model->status_id >= 6) echo "ui-icon-check";
			else echo "ui-icon-black ui-icon-play";
		?>" style="display:inline-block;" title="<?= $model->status->name ?>">
</span>

<span class="glyphicon glyphicon<?
			if ($model->status_id >= 6) echo "-ok text-success";
			else echo "-play text-default";
		?>" style="display:inline-block;" title="<?= $model->status->name ?>">
</span>

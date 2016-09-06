<? if (Project::GetSelected()) { ?>
<span class="project-view">
	<?= Project::GetSelected()->name; ?>
	<span onclick="start_edit();" class="ui-icon ui-icon-pencil" style="display:inline-block;"></span>
</span>
<span class="project-edit" style="display:none;">
	<?= CHTML::dropDownList('project', Project::GetSelected()->id, CHTML::listData(Project::model()->findAll(new CDBCriteria(
		array(
				'condition'=>'u.user_id = :uid',
				'join'=>'INNER JOIN user_has_project AS u ON u.project_id = t.id',
				'params'=>array(':uid'=>Yii::app()->user->id),
			)
	)), 'id', 'name')) ?>
	<span onclick="update_project();" class="ui-icon ui-icon-check" style="display:inline-block;"></span>
	<span onclick="cancel_changes();" class="ui-icon ui-icon-close" style="display:inline-block;"></span>
</span>
<script>
	function start_edit() {
		$(".project-view").hide();
		$(".project-edit").show();
	}
	function update_project() {
		$.ajax({
			url : '<?= CHTML::normalizeUrl(['/project/SetSelected', 'id'=>''])?>' + $("#project").val(),
			success : function() {window.location.reload();},
		});
	}
	function cancel_changes() {
		$(".project-view").show();
		$(".project-edit").hide();
	}
</script>
<? } ?>
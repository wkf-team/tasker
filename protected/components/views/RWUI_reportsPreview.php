<style>
#reports {
	position: fixed;
	width: 360px;
	right: 280px;
	top: 150px;
	padding: 0.4em;
	z-index:20;
}
#reports h3 {
	margin: 0;
	padding: 0.4em;
	text-align: center;
}
#btnReports {
	//position: relative;
	//right: 240px;
	//top: 150px;
}
</style>

<div class="toggler">
  <div id="reports" class="ui-widget-content ui-corner-all">
    <h3 class="ui-widget-header ui-corner-all">Отчеты</h3>
    <p>
		<?php
			$this->widget('RW_GoalsComplete'); 
			$this->widget('RW_UsersBalance'); 
			$this->widget('RW_TermsBreak'); 
		?>
    </p>
  </div>
</div>
<button id="btnReports">Отчеты</button>

<script>

$(function () {
    $( "#btnReports" ).button({
      icons: {
        primary: "ui-icon-seek-prev"
      },
      text: false
    }).click(function() {
		ShowHideReports();
		return false;
    });
	$( "#reports" ).hide();
	
    function ShowHideReports() { 
		$( "#reports" ).toggle( "drop", {}, 500 );
		if ($("#btnReports span:first").hasClass("ui-icon-seek-prev")) {
			$("#btnReports span:first").removeClass("ui-icon-seek-prev").addClass("ui-icon-seek-next");
		} else {
			$("#btnReports span:first").removeClass("ui-icon-seek-next").addClass("ui-icon-seek-prev");
		}
    };

});
</script>
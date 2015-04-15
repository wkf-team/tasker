<?php
/* @var $this TicketController */
/* @var $model Ticket */
/* @var $form CActiveForm */
?>
<style>
div.wide, div.wide a {
	color:black;
}
div.wide a:hover {
	color:#56B304;
}
</style>
<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model, 'asearch'); ?>
		<?php echo $form->textField($model,'asearch', array('size'=>100)); ?>
	</div>
	
	<div class="row buttons">
		<?php echo CHtml::submitButton('Поиск'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->

<script>
  $(function() {
    var attributes = [
	<?php
		$attrs = $model->attributeLabels();
		foreach ($attrs as $val => $label) echo "{value: '$val', label:'$val.$label', desc:'$label'},";
	?>
    ];
	
    function split( val ) {
      return val.split( / \s*/ );
    }
    function extractLast( term ) {
      return split( term ).pop();
    }
 
    $( "#Ticket_asearch" )
	// don't navigate away from the field on tab when selecting an item
	.bind( "keydown", function( event ) {
        if ( event.keyCode === $.ui.keyCode.TAB &&
            $( this ).data("ui-autocomplete").menu.active ) {
          event.preventDefault();
        }
      })
	.autocomplete({
      minLength: 0,
	  delay: 500,
	  source: function( request, response ) {
          // delegate back to autocomplete, but extract the last term
          response( $.ui.autocomplete.filter(
            attributes, extractLast( request.term ) ) );
        },
      focus: function( event, ui ) {
		// prevent value inserted on focus
        //$( "#Ticket_asearch" ).val( ui.item.value );
        return false;
      },
      select: function( event, ui ) {
		var terms = split( this.value );
          // remove the current input
          terms.pop();
          // add the selected item
          terms.push( ui.item.value );
          // add placeholder to get the comma-and-space at the end
          terms.push( "" );
          this.value = terms.join( " " );
          return false;
        //$( "#Ticket_asearch" ).val( ui.item.value ); 
        return false;
      }
    })
    .data("ui-autocomplete")._renderItem = function( ul, item ) {
      return $( "<li>" )
        .append( "<a>" + item.desc + " (" + item.value + ")</a>" )
        .appendTo( ul );
    };
  });
  </script>
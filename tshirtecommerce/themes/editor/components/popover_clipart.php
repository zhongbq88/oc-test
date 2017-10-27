<?php
$addons = $GLOBALS['addons'];
?>
<div id="options-add_item_clipart" class="dg-options">
	<div class="dg-options-toolbar">
		<div aria-label="First group" role="group" class="btn-group btn-group-lg">						
			<div id="item-print-colors"></div>
			<div id="clipart-colors" class="pull-left">
				<div id="list-clipart-colors" class="list-colors"></div>
			</div>
			<div class="rotate pull-left">
				<span class="rotate-values"><input type="text" value="0" class="rotate-value" id="clipart-rotate-value" /></span>			
				<span class="rotate-deg"></span>
			</div>
			<div class="toolbar-size pull-left">
				<input type="text" size="2" id="clipart-width" readonly disabled> 
				<a href="javascript:void(0);" class="icon-ui-lock" title="<?php echo lang('designer_clipart_edit_unlock_proportion'); ?>">
					<i class="fa fa-lock" aria-hidden="true"></i>
				</a>
				<input type="text" size="2" id="clipart-height" readonly disabled>
				
				<input type="checkbox" style="display:none;" class="ui-lock" id="clipart-lock" />
			</div>
		</div>
	</div>
</div>
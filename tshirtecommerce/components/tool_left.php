<?php
$settings = $GLOBALS['settings'];
$addons = $GLOBALS['addons'];
?>
<div class="col-left">

	
	<div id="dg-left" class="width-100">
		<div class="dg-box width-100">
			<ul class="menu-left">
				<li <?php echo cssShow($settings, 'show_product'); ?>>
					<a href="#dg-products" class="view_change_products" title="" data-toggle="modal" data-target="#dg-products">
						<i class="glyphicons t-shirt"></i> <?php echo lang('designer_menu_choose_product'); ?>
					</a>
				</li>			
				<li <?php echo cssShow($settings, 'show_add_text'); ?>>
					<a href="javascript:void(0);" class="add_item_text" title="">
						<i class="glyphicons text_bigger"></i> <?php echo lang('designer_menu_add_text'); ?>
					</a>
				</li>
				
				<li <?php echo cssShow($settings, 'show_add_art'); ?>>
					<a href="#dg-cliparts" class="add_item_clipart" title="" data-toggle="modal" data-target="#dg-cliparts">
						<i class="glyphicons picture"></i> <?php echo lang('designer_menu_add_art'); ?>
					</a>
				</li>							
				<li <?php echo cssShow($settings, 'show_add_upload'); ?>>
					<a href="#dg-myclipart" title="" data-toggle="modal" data-target="#dg-myclipart">
						<i class="glyphicons cloud-upload"></i> <?php echo lang('designer_menu_upload_image'); ?>
					</a>
				</li>
				
				<li <?php echo cssShow($settings, 'show_add_team'); ?>>
					<a href="javascript:void(0);" class="add_item_team" title="">
						<i class="glyphicons soccer_ball"></i> <?php echo lang('designer_menu_name_number'); ?>
					</a>
				</li>
				<li>
					<a href="javascript:void(0);" class="add_item_mydesign" <?php echo cssShow($settings, 'show_my_design'); ?>>
						<i class="glyphicons user"></i> <?php echo lang('designer_menu_my_design'); ?>
					</a>
				</li>				
				<li <?php echo cssShow($settings, 'show_add_qrcode'); ?>>
					<a href="javascript:void(0);" class="add_item_qrcode" title="">
						<i class="glyphicons qrcode"></i> <?php echo lang('designer_menu_add_qrcode'); ?>
					</a>
				</li>
				<?php $addons->view('menu-left'); ?>
			</ul>
		</div>
		
		<div class="dg-box width-100 div-layers no-active" <?php echo cssShow($settings, 'show_layers'); ?>>
			<div class="layers-toolbar">
				<button type="button" class="btn btn-default">
					<i class="fa fa-long-arrow-down"></i>
					<i class="fa fa-long-arrow-up"></i>
				</button>
				<button type="button" class="btn btn-default btn-sm">
					<i class="fa fa-angle-right"></i>						
				</button>
			</div>
				
			<div class="accordion">
				<h3><?php echo lang('designer_menu_login_layers'); ?></h3>
				<div id="dg-layers">
					<ul id="layers"></ul>
				</div>
			</div>
		</div>
	</div>
</div>
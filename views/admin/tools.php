<div class="wrap sln-bootstrap">
	<h1><?php _e( 'Tools', 'salon-booking-system' ) ?></h1>
</div>
<div class="clearfix"></div>
<div id="sln-salon--admin" class="container-fluid wpcontent">
	<form>
		<div class="sln-tab" id="sln-tab-general">
			<div class="sln-box sln-box--main">
				<h2 class="sln-box-title"><?php _e('Settings debug','salon-booking-system') ?></h2>
				<div class="row">
					<div class="col-sm-12 form-group">
						<h6 class="sln-fake-label"><?php _e('Copy and paste into a text file the informations of this field and provide them to Salon Booking support.','salon-booking-system')?></h6>
					</div>
					<div class="col-sm-8 form-group sln-input--simple">
						<textarea rows="7" id="tools-textarea" class='tools-textarea'><?php echo $info; ?></textarea>
						<p class="help-block"><?php _e('Just click inside the textarea and copy (Ctrl+C)','salon-booking-system')?></p>
					</div>
				</div>
			</div>
		</div>
	</form>
	<form method="post" action="<?php echo admin_url('admin.php?page=' . SLN_Admin_Tools::PAGE)?>">
		<div class="sln-tab" id="sln-tab-general">
			<div class="sln-box sln-box--main">
				<h2 class="sln-box-title"><?php _e('Settings import','salon-booking-system') ?></h2>
				<div class="row">
					<div class="col-sm-12 form-group">
						<h6 class="sln-fake-label"><?php _e('Copy and paste into this field settings of the plugin to import settings into the current wordpress install.','salon-booking-system')?></h6>
					</div>
					<div class="col-sm-8 form-group sln-input--simple">
						<textarea rows="7" id="tools-import" name="tools-import"></textarea>
<!--						<p class="help-block"><?php _e('Just click inside the textarea and copy (Ctrl+C)','salon-booking-system')?></p>-->
					</div>
				</div>
				<div class="row">
					<div class="col-sm-2 form-group col-md-offset-7">
						<input  disabled type="submit" class="btn btn-default" value="Import" name="sln-tools-import" id="submit-import">
					</div>
				</div>
			</div>
		</div>
	</form>
</div>

<script>
	jQuery(function($){
		jQuery('#wpbody #tools-textarea').click(function() {
			jQuery('#tools-textarea').select();
		});
		
		jQuery('#tools-import').on('keyup', function(){
			var $textarea = jQuery('#tools-import').val();
			var disable = ($textarea.length == '');
			$("#submit-import").prop("disabled", disable);
		});
		
		jQuery('#submit-import').on('click', function(e){
			if (!confirm('Are you sure to continue?')) {
				e.preventDefault();
				$(document.activeElement).blur();
			}
		});

	});
</script>

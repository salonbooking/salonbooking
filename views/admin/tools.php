<div class="wrap sln-bootstrap">
	<h1><?php _e( 'Tools', 'salon-booking-system' ) ?></h1>
</div>
<div class="clearfix"></div>
<div id="sln-salon--admin" class="container-fluid wpcontent">
	<?php if (!empty($versionToRollback)): ?>
            <?php echo $plugin->loadView('admin/_tools_rollback', compact('versionToRollback', 'currentVersion', 'isFree')) ?>
	<?php endif ?>
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

	<form method="post" action="<?php echo admin_url('admin.php?page=' . SLN_Admin_Tools::PAGE)?>">
		<div class="sln-tab" id="sln-tab-import-data">
			<div class="sln-box sln-box--main">
				<div class="row">
					<div class="col-xs-6 col-sm-5">
						<div class="row">
							<div class="col-sm-12 form-group">
								<h2 class="sln-box-title"><?php _e('Import customers','salon-booking-system') ?></h2>
								<h6 class="sln-fake-label"><?php _e('Import customers from other platforms using a csv file that respect our csv sample file structure','salon-booking-system')?></h6>
							</div>
							<div class="col-sm-12 form-group sln-input--simple sln-logo-box">
								<div id="import-customers-drag" class="preview-logo">
									<div class="info">
										<span class="glyphicon glyphicon-upload" aria-hidden="true"></span>
										<span class="text"><?php _e('drag your csv file here to import customers', 'salon-booking-system') ?></span>
									</div>
									<div class="alert alert-success hide" role="alert"><?php _e('Well done! Your import has been successfully completed.', 'salon-booking-system') ?></div>
									<div class="alert alert-danger hide" role="alert"><?php _e('Error! Something is gone wrong.', 'salon-booking-system') ?></div>

									<div class="progress hide">
										<div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar"
										     aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
										</div>
									</div>
								</div>
							</div>
							<div class="col-sm-12 form-group">
								<div class="pull-right">
									<button type="button" class="sln-btn sln-btn--main sln-btn--big" data-action="sln_import" data-target="import-customers-drag">
										<?php _e('Import', 'salon-booking-system') ?>
									</button>
								</div>
							</div>
						</div>
					</div>

					<div class="col-xs-6 col-xs-offset-0 col-sm-5 col-sm-offset-2">
						<div class="row">
							<div class="col-sm-12 form-group">
								<h2 class="sln-box-title"><?php _e('Import services','salon-booking-system') ?></h2>
								<h6 class="sln-fake-label"><?php _e('Import services from other platforms using a csv file that respect our csv sample file structure','salon-booking-system')?></h6>
							</div>
							<div class="col-sm-12 form-group sln-input--simple sln-logo-box">
								<div id="import-services-drag" class="preview-logo">
									<div class="info">
										<span class="glyphicon glyphicon-upload" aria-hidden="true"></span>
										<span class="text"><?php _e('drag your csv file here to import services', 'salon-booking-system') ?></span>
									</div>
									<div class="alert alert-success hide" role="alert"><?php _e('Well done! Your import has been successfully completed.', 'salon-booking-system') ?></div>
									<div class="alert alert-danger hide" role="alert"><?php _e('Error! Something is gone wrong.', 'salon-booking-system') ?></div>

									<div class="progress hide">
										<div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar"
										     aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
										</div>
									</div>
								</div>
							</div>
							<div class="col-sm-12 form-group">
								<div class="pull-right">
									<button type="button" class="sln-btn sln-btn--main sln-btn--big" data-action="sln_import" data-target="import-services-drag">
										<?php _e('Import', 'salon-booking-system') ?>
									</button>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-xs-6 col-sm-5">
						<div class="row">
							<div class="col-sm-12 form-group">
								<h2 class="sln-box-title"><?php _e('Import assistants','salon-booking-system') ?></h2>
								<h6 class="sln-fake-label"><?php _e('Import assistants from other platforms using a csv file that respect our csv sample file structure','salon-booking-system')?></h6>
							</div>
							<div class="col-sm-12 form-group sln-input--simple sln-logo-box">
								<div id="import-assistants-drag" class="preview-logo">
									<div class="info">
										<span class="glyphicon glyphicon-upload" aria-hidden="true"></span>
										<span class="text"><?php _e('drag your csv file here to import assistants', 'salon-booking-system') ?></span>
									</div>
									<div class="alert alert-success hide" role="alert"><?php _e('Well done! Your import has been successfully completed.', 'salon-booking-system') ?></div>
									<div class="alert alert-danger hide" role="alert"><?php _e('Error! Something is gone wrong.', 'salon-booking-system') ?></div>

									<div class="progress hide">
										<div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar"
										     aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
										</div>
									</div>
								</div>
							</div>
							<div class="col-sm-12 form-group">
								<div class="pull-right">
									<button type="button" class="sln-btn sln-btn--main sln-btn--big" data-action="sln_import" data-target="import-assistants-drag">
										<?php _e('Import', 'salon-booking-system') ?>
									</button>
								</div>
							</div>
						</div>
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

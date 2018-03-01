<div class="wrap sln-bootstrap">
	<h1><?php _e( 'Tools', 'salon-booking-system' ) ?></h1>
</div>
<div class="clearfix"></div>
<div id="sln-salon--admin" class="container-fluid wpcontent sln-calendar--wrapper sln-calendar--wrapper--loading">
<div class="sln-calendar--wrapper--sub sln-tools__wrapper" style="opacity: 0;">
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
					<div class="col-xs-12 form-group sln-input--simple">
						<textarea id="tools-textarea" class='tools-textarea'><?php echo $info; ?></textarea>
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
					<div class="col-xs-12 form-group sln-input--simple">
						<textarea id="tools-import" name="tools-import"></textarea>
<!--						<p class="help-block"><?php _e('Just click inside the textarea and copy (Ctrl+C)','salon-booking-system')?></p>-->
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 form-group">
						<input  disabled type="submit" class="btn_ btn-default_ sln-btn sln-btn--main sln-btn--big" value="Import" name="sln-tools-import" id="submit-import">
					</div>
				</div>
			</div>
		</div>
	</form>

	<form method="post" action="<?php echo admin_url('admin.php?page=' . SLN_Admin_Tools::PAGE)?>">
		<div class="sln-tab" id="sln-tab-import-data">
			<div class="sln-box sln-box--main">
				<div class="row">
					<div class="col-xs-12 col-lg-6">
						<div class="row">
							<div class="col-sm-12 form-group">
								<h2 class="sln-box-title"><?php _e('Import customers','salon-booking-system') ?></h2>
								<h6 class="sln-fake-label"><?php _e('Import customers from other platforms using a csv file that respect our csv sample file structure','salon-booking-system')?></h6>
							</div>
							<div class="col-sm-12 form-group sln-input--simple sln-logo-box">
								<div id="import-customers-drag" class="preview-logo">
									<br><br>
									<div class="info">
										<span class="glyphicon glyphicon-upload" aria-hidden="true"></span>
										<span class="text" placeholder="<?php _e('drag your csv file here to import customers', 'salon-booking-system') ?>">
											<?php _e('drag your csv file here to import customers', 'salon-booking-system') ?></span>
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
								<div class="row">
									<div class="col-sm-6">
										<a href="<?php echo SLN_PLUGIN_URL . '/csv-import-samples/Salon import sample customers.csv'; ?>"><?php _e('download a csv sample file', 'salon-booking-system') ?></a>
									</div>
									<div class="col-sm-6">
										<button type="button" class="sln-btn sln-btn--main sln-btn--big pull-right" data-action="sln_import" data-target="import-customers-drag"
										        data-loading-text="<span class='glyphicon glyphicon-repeat sln-import-loader' aria-hidden='true'></span> <?php _e('loading', 'salon-booking-system') ?>">
											<?php _e('Import', 'salon-booking-system') ?>
										</button>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="col-xs-12 col-lg-6">
						<div class="row">
							<div class="col-sm-12 form-group">
								<h2 class="sln-box-title"><?php _e('Import services','salon-booking-system') ?></h2>
								<h6 class="sln-fake-label"><?php _e('Import services from other platforms using a csv file that respect our csv sample file structure','salon-booking-system')?></h6>
							</div>
							<div class="col-sm-12 form-group sln-input--simple sln-logo-box">
								<div id="import-services-drag" class="preview-logo">
									<br><br>
									<div class="info">
										<span class="glyphicon glyphicon-upload" aria-hidden="true"></span>
										<span class="text" placeholder="<?php _e('drag your csv file here to import services', 'salon-booking-system') ?>">
											<?php _e('drag your csv file here to import services', 'salon-booking-system') ?></span>
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
								<div class="row">
									<div class="col-sm-6">
										<a href="<?php echo SLN_PLUGIN_URL . '/csv-import-samples/Salon import sample services.csv'; ?>"><?php _e('download a csv sample file', 'salon-booking-system') ?></a>
									</div>
									<div class="col-sm-6">
										<button type="button" class="sln-btn sln-btn--main sln-btn--big pull-right" data-action="sln_import" data-target="import-services-drag"
										        data-loading-text="<span class='glyphicon glyphicon-repeat sln-import-loader' aria-hidden='true'></span> <?php _e('loading', 'salon-booking-system') ?>">
											<?php _e('Import', 'salon-booking-system') ?>
										</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-xs-12 col-lg-6">
						<div class="row">
							<div class="col-sm-12 form-group">
								<h2 class="sln-box-title"><?php _e('Import assistants','salon-booking-system') ?></h2>
								<h6 class="sln-fake-label"><?php _e('Import assistants from other platforms using a csv file that respect our csv sample file structure','salon-booking-system')?></h6>
							</div>
							<div class="col-sm-12 form-group sln-input--simple sln-logo-box">
								<div id="import-assistants-drag" class="preview-logo">
									<br><br>
									<div class="info">
										<span class="glyphicon glyphicon-upload" aria-hidden="true"></span>
										<span class="text" placeholder="<?php _e('drag your csv file here to import assistants', 'salon-booking-system') ?>">
											<?php _e('drag your csv file here to import assistants', 'salon-booking-system') ?></span>
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
								<div class="row">
									<div class="col-sm-6">
										<a href="<?php echo SLN_PLUGIN_URL . '/csv-import-samples/Salon import sample assistants.csv'; ?>"><?php _e('download a csv sample file', 'salon-booking-system') ?></a>
									</div>
									<div class="col-sm-6">
										<button type="button" class="sln-btn sln-btn--main sln-btn--big pull-right" data-action="sln_import" data-target="import-assistants-drag"
										        data-loading-text="<span class='glyphicon glyphicon-repeat sln-import-loader' aria-hidden='true'></span> <?php _e('loading', 'salon-booking-system') ?>">
											<?php _e('Import', 'salon-booking-system') ?>
										</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div id="import-matching-modal" class="modal" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content">
<!--						<div class="modal-header"></div>-->
						<div class="modal-body">
							<div class="row">
								<div class="col-md-12 form-group">
									<h2 class="sln-box-title"><?php _e('You need to match your CSV file data with Salon Booking database','salon-booking-system') ?></h2>
									<h6 class="sln-fake-label"><?php _e('Select for each column the corresponding one inside your file.','salon-booking-system')?></h6>
								</div>
								<div class="col-md-12">
									<table class="table sln-import-table" cellspacing="0"><tbody></tbody></table>
								</div>
								<div class="col-md-12">
									<div class="row">
										<div class="col-md-8">
											<div class="alert alert-danger hide">
												<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
												<?php _e('Please provide all requested columns', 'salon-booking-system') ?>
											</div>
										</div>
										<div class="col-md-4">
											<button type="button" class="sln-btn sln-btn--main sln-btn--big pull-right" data-action="sln_import_matching"
											        data-loading-text="<span class='glyphicon glyphicon-repeat sln-import-loader' aria-hidden='true'></span> <?php _e('loading', 'salon-booking-system') ?>">
												<?php _e('Import', 'salon-booking-system') ?>
											</button>
										</div>
									</div>
								</div>
							</div>
						</div>
<!--						<div class="modal-footer"></div>-->
					</div>
				</div>
			</div>

		</div>
	</form>

</div>
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

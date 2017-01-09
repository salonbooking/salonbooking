<!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist">
	<li class="col-xs-12 col-sm-4 col-md-4"><div><?php echo sprintf(__('Welcome back %s!','salon-booking-system'), $data['user_name']); ?></div></li>
	<li class="col-xs-12 col-sm-4 col-md-4 active" role="presentation"><a href="#new" aria-controls="new" role="tab" data-toggle="tab"><?php _e('Next appointments', 'salon-booking-system') ?></a></li>
	<li class="col-xs-12 col-sm-4 col-md-4" role="presentation"><a href="#old" aria-controls="old" role="tab" data-toggle="tab"><?php _e('Reservations history', 'salon-booking-system') ?></a></li>
</ul>

<!-- Tab panes -->
<div class="tab-content sln-salon-my-account-tab-content">
	<div role="tabpanel" class="tab-pane sln-salon-my-account-tab-pane active" id="new">
		<?php if($data['cancelled']): ?>
			<p class="hint"><?php _e('The booking has been cancelled', 'salon-booking-system'); ?></p>
		<?php endif ?>
		<?php if (!empty($data['new']['items'])):?>
			<p class="hint">
				<?php _e('Here you have your next reservations with us, pay attention to the \'pending\' reservations', 'salon-booking-system'); ?>
			</p>
			<?php
			$data['table_data']         = $data['new'];
			$data['table_data']['mode'] = 'new';

			include '_salon_my_account_details_table.php';
			unset($data['table_data']);
			?>
		<?php else: ?>
			<p class="hint"><?php _e('You don\'t have upcoming reservations, do you want to re-schedule your last appointment with us?', 'salon-booking-system'); ?></p>
			<?php
			if (!empty($data['history']['items'])) {
				$historySuccesfulItems = $data['history_successful']['items'];

				$data['table_data'] = array(
					'items' => $historySuccesfulItems,
					'mode'  => 'new',
				);

				include '_salon_my_account_details_table.php';
				unset($data['table_data']);
			}
			?>
		<?php endif; ?>
		<br>
		<div class="row">
			<div class="col-xs-10 col-sm-6 col-md-4">
				<div class="sln-btn sln-btn--emphasis sln-btn--medium sln-btn--fullwidth">
					<a href="<?php echo $data['booking_url'] ?>"><?php _e('MAKE A NEW RESERVATION', 'salon-booking-system') ?></a>
				</div>
			</div>
		</div>
	</div>
	<div role="tabpanel" class="tab-pane sln-salon-my-account-tab-pane" id="old">
		<?php if (!empty($data['history']['items'])):?>
			<p class="hint"><?php _e('Here you have your past reservations, you can submit a review or re-schedule an appointment', 'salon-booking-system'); ?></p>

			<div id="sln-salon-my-account-history-content">
				<?php
				$data['table_data']         = $data['history'];
				$data['table_data']['mode'] = 'history';

				include '_salon_my_account_details_table.php';
				unset($data['table_data']);
				?>
			</div>

			<div class="row">
				<div class="col-xs-1 col-sm-1 col-md-1 pull-right">
					<a id="next_history_page_btn" href="load_more" onclick="slnMyAccount.loadNextHistoryPage(); return false;" style="color: #b6b6b6"><div class="glyphicon-ring"><span class="glyphicon glyphicon-plus glyphicon-bordered" aria-hidden="true"></span></div></a>
				</div>
			</div>
		<?php else: ?>
			<p class="hint"><?php _e('No bookings', 'salon-booking-system'); ?></p>
		<?php endif; ?>
		<div class="row">
			<div class="col-xs-10 col-sm-6 col-md-4">
				<div class="sln-btn sln-btn--emphasis sln-btn--medium sln-btn--fullwidth">
					<a href="<?php echo $data['booking_url'] ?>"><?php _e('MAKE A NEW RESERVATION', 'salon-booking-system') ?></a>
				</div>
			</div>
		</div>
	</div>


	<div id="ratingModal" class="modal fade" role="dialog" tabindex="-1">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title"></h4>
				</div>
				<div class="modal-body">
					<div id="step1">
						<p><?php _e('Hi','salon-booking-system');?> <?php echo $data['user_name'] ?>!</p>
						<p><?php _e('How was your expirience with us this time? (required)','salon-booking-system');?></p>
						<p><textarea id="" placeholder="<?php _e('please, drop us some lines to understand if your expirience has been  in line  with your expectrations','salon-booking-system');?>"></textarea></p>
						<p>
						<div class="rating" id="<?php echo $item['id']; ?>"></div>
						<span><?php _e('Rate our service (required)','salon-booking-system');?></span>
						</p>
						<p>
							<button type="button" class="btn btn-primary" onclick="slnMyAccount.sendRate();"><?php _e('Send your review','salon-booking-system');?></button>
							<button type="button" class="btn btn-default" data-dismiss="modal"><?php _e('Cancel','salon-booking-system');?></button>
						</p>
					</div>
					<div id="step2">
						<p><?php _e('Thank you for your review. It will help us improving our services.','salon-booking-system');?></p>
						<p><?php _e('We hope to see you again at','salon-booking-system');?> <?php echo $data['gen_name']; ?></p>
					</div>
				</div>
				<div class="modal-footer"></div>
			</div>
		</div>
	</div>
</div>

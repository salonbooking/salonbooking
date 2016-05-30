<?php   // algolplus
/**
 * @var SLN_Plugin                $plugin
 * @var SLN_Wrapper_Booking       $booking
 */
if(!isset($data['to'])){
	$data['to'] = $plugin->getSettings()->getSalonEmail();
}

$data['subject'] = __('Booking was rated','salon-booking-system');

include dirname(__FILE__).'/_header.php';
?>

	<tr style="font-family: Arial, Helvetica, sans-serif; color: #888;">
		<td height="105" valign="middle" bgcolor="#f2f2f2">
			<p style="margin-left: 49px; margin-right: 49px;"><?php _e('Dear Administrator,','salon-booking-system') ?></p>
			<p style="margin-left: 49px; margin-right: 49px;">
				<?php
				_e('your customers','salon-booking-system');
				$usermeta = get_user_meta($booking->getUserId());
				echo ' ' . $usermeta['first_name'][0] . ' ' . $usermeta['last_name'][0] . ' ';
				_e('has submitted a new review on his last visit at','salon-booking-system');
				echo ' ' . $plugin->getSettings()->get('gen_name') . '.';
				?>
			</p>
			<hr style="border: solid 1px #fff; margin: 0 16px;">
		</td>
	</tr>

	<tr style="font-family: Arial, Helvetica, sans-serif; color: #888;">
		<td height="105" valign="middle" bgcolor="#f2f2f2">
			<table width="502" border="0" align="left" cellpadding="0" cellspacing="0"  style="margin-left: 49px; margin-right: 49px;">
				<tbody>
				<tr>
					<td width="194" valign="top">
						<p>
							<?php
							$comments = get_comments("post_id=" . $booking->getId());
							echo (isset($comments[0]) ? '“' .$comments[0]->comment_content . '”' : '');
							?>
						</p>
					</td>

					<td width="147" align="center" valign="top">
						<p style="padding-left: 20px;">
							<a href="<?php echo esc_url(add_query_arg(array('p' => $booking->getId()), admin_url('edit-comments.php'))); ?>#salon-review"style="
										display: inline-block;
									    padding: 16px 12px;
									    margin-bottom: 0;
									    font-size: 12px;
									    font-weight: 400;
									    line-height: 1.42857143;
									    text-align: center;
									    white-space: nowrap;
									    vertical-align: middle;
									    -ms-touch-action: manipulation;
									    touch-action: manipulation;
									    cursor: pointer;
									    -webkit-user-select: none;
									    -moz-user-select: none;
									    -ms-user-select: none;
									    user-select: none;
									    background-image: none;
									    border: 1px solid transparent;
									    color: #fff;
									    background-color: #114566;
									    text-decoration: none;">
								<?php _e('READ THE FULL REVIEW','salon-booking-system'); ?></a>
						</p>
					</td>
				</tr>
				</tbody>
			</table>
		</td>
	</tr>

<?php
include dirname(__FILE__).'/_footer.php';
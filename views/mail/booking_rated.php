<?php   // algolplus
/**
 * @var SLN_Plugin                $plugin
 * @var SLN_Wrapper_Booking       $booking
 */
if(!isset($data['to'])){
	$data['to'] = get_option('admin_email');
}

$data['subject'] = __('Booking was rated','sln');

include dirname(__FILE__).'/_header.php';
?>

	<tr>
		<td height="105" valign="middle" bgcolor="#f2f2f2" style="border-bottom:2px solid #fff;">
			<p style="margin-left: 49px; margin-right: 49px;"><?php _e('Dear Administrator,','sln') ?></p>
			<p style="margin-left: 49px; margin-right: 49px;">
				<?php
				_e('your customers','sln');
				$usermeta = get_user_meta($booking->getUserId());
				echo ' ' . $usermeta['first_name'][0] . ' ' . $usermeta['last_name'][0] . ' ';
				_e('has submitted a new review on his last visit at','sln');
				echo ' ' . $plugin->getSettings()->get('gen_name') . '.';
				?>
			</p>
		</td>
	</tr>

	<tr>
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
							<a href="<?php echo esc_url(add_query_arg(array('p' => $booking->getId()), admin_url('edit-comments.php'))); ?>"style="
										display: inline-block;
									    padding: 6px 12px;
									    margin-bottom: 0;
									    font-size: 14px;
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
								<?php _e('READ THE FULL REVIEW','sln'); ?></a>
						</p>
					</td>
				</tr>
				</tbody>
			</table>
		</td>
	</tr>

<?php
include dirname(__FILE__).'/_footer.php';
<table width="502" border="0" align="left" cellpadding="0" cellspacing="0">
	<tbody>
		<tr>
			<td width="502" align="center" valign="top">
				<table width="457" border="0" align="center" cellpadding="0" cellspacing="0">
					<tbody>
						<tr>
							<td  width="300" height="100" align="left" valign="middle" bgcolor="#ffffff" style="font-family:Arial,Helvetica,sans-serif;font-size:16px;color:rgb(102,102,102);font-weight:normal">
								<?php _e('To confirm your booking click on the button and complete the payment', 'salon-booking-system'); ?>
							</td>
                            <td  width="157" height="100" align="left" valign="middle" bgcolor="#ffffff" style="font-family:Arial,Helvetica,sans-serif;font-size:16px;color:rgb(102,102,102);font-weight:normal; text-align: right;">
								<a href="<?php echo $booking->getPayUrl()?>" style="display:inline-block; background: rgb(16,110,197); font-family:Arial,Helvetica,sans-serif;font-size:16px;color:rgb(255,255,255);font-weight:normal; text-decoration: none; padding: 10px 16px; margin: 40px 0; text-align: center; border-radius: 4px;">
									Pay <?php if($booking->getDeposit()): ?>
									Deposit <?php echo $plugin->format()->moneyFormatted($booking->getDeposit()) ?>
									<?php else: ?>
									<?php echo $plugin->format()->moneyFormatted($booking->getAmount()) ?>
									<?php endif ?>
								</a>
							</td>
						</tr>
					</tbody>
				</table>
			</td>
		</tr>
	</tbody>
</table>

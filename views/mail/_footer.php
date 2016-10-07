<?php if(isset($booking) && (!isset($forAdmin) || !$forAdmin) && $booking->getEmailCancellationDetails($cancellationText,$bookingMyAccountUrl) ):?>
<tr>
    <td height="73" align="center" valign="top" bgcolor="#f2f2f2"
        style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#666666; font-weight:normal;">
        <hr style="border: solid 1px #fff; margin: 0 16px;">

        <table width="502" border="0" align="center" cellpadding="0" cellspacing="0">
          <tbody><tr>
            <td width="272" align="center" valign="middle" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#666666; font-weight:normal;"><?php echo sprintf(__('Booking cancellation is allowed <b>until %s</b> <br> before the reservation','salon-booking-system'), $cancellationText); ?></td>
            <td align="right" valign="middle" style="font-family:Arial, Helvetica, sans-serif; font-size:16px; color:#666666; font-weight:normal;">
            <a href="<?php echo $bookingMyAccountUrl?>" style="display:inline-block; background: #EE0707; font-family:Arial,Helvetica,sans-serif;font-size:14px;color:#fff;font-weight:bold; text-decoration: none; text-transform: uppercase; padding: 10px 30px; margin: 20px 0; text-align: center; border-radius: 4px; letter-spacing: 1px;"><?php echo __('Cancel booking','salon-booking-system') ?></a>
            </td>
          </tr>
        </tbody>
        </table>
        </td>
</tr>
<?php endif;?>
</table>
</body>
</html>
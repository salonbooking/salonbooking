<?php
$showPrices = !$plugin->getSettings()->isHidePrices();
/** @var SLN_Wrapper_Booking $booking */
?>
<table width="502" border="0" align="left" cellpadding="0" cellspacing="0">
          <tbody><tr>
            <td width="157" align="center" valign="top" style="border-right:2px solid #f2f2f2;">
              <table width="306" border="0" align="center" cellpadding="0" cellspacing="0">
              <tbody><tr>
                <td align="left" valign="top">&nbsp;</td>
              </tr>
              <tr>
                <td height="25" align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#cc3333; font-weight:normal;"><?php _e('Date', 'salon-booking-system')?></td>
                <td height="25" align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#cc3333; font-weight:normal;"><?php _e('Time','salon-booking-system') ?></td>
                <td height="25" align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#cc3333; font-weight:normal;"><?php _e('Service','salon-booking-system') ?></td>
                <td height="25" align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#cc3333; font-weight:normal;"><?php _e('Attendant','salon-booking-system') ?></td>
              </tr>
              <?php $printDate = true; ?>
              <?php foreach($booking->getBookingServices()->getItems() as $bookingService): ?>
              <tr>
                <td height="36" align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:15px; color:#666666; font-weight:bold;"><?php if ($printDate) {$printDate = false; echo $plugin->format()->date($bookingService->getStartsAt());} ?></td>
                <td height="25" align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:15px; color:#666666; font-weight:bold;"><?php echo $plugin->format()->time($bookingService->getStartsAt()) ?></td>
                <td height="20" align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#666666; font-weight:bold;"><?php echo $bookingService->getService()->getName(); ?></td>
                <td height="20" align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#666666; font-weight:bold;"><?php echo $bookingService->getAttendant()->getName(); ?></td>
              </tr>
              <?php endforeach ?>
              <tr>
                <td align="left" valign="top">&nbsp;</td>
              </tr>
            </tbody>
            </table>
            </td>

            <td width="147" align="center" valign="top"><table width="112" border="0" align="center" cellpadding="0" cellspacing="0">
              <tbody><tr>
                <td align="left" valign="top">&nbsp;</td>
              </tr>
              <tr>
                <td height="25" align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#cc3333; font-weight:normal;"><?php if($showPrices){?><?php _e('Total amount', 'salon-booking-system') ?><?php } ?></td>
              </tr>
              <tr>
                <td height="36" align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:18px; color:#666666; font-weight:bold;"><?php if($showPrices){?><?php echo $plugin->format()->money($booking->getAmount()) ?><?php } ?></td>
              </tr>
              <tr>
                <td height="28" align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#cc3333; font-weight:normal;"><?php _e('Status','salon-booking-system')?></td>
              </tr>
              <tr>
                <td align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:18px; color:#666666; font-weight:bold;">
                    <?php echo SLN_Enum_BookingStatus::getLabel($booking->getStatus()) ?>
                    <?php if( $booking->getDeposit() && $booking->hasStatus(SLN_Enum_BookingStatus::PAID) ){ ?>

                        <span style="font-size: 14px; font-family: Arial, Helvetica, sans-serif; font-weight:normal;"><br/>Deposit <?php echo $plugin->format()->money($booking->getDeposit()) ?></span>

                    

                    <?php } ?>
                </td>
              </tr>
              <tr>
                <td align="left" valign="top">&nbsp;</td>
              </tr>
            </tbody></table></td>
          </tr>
        </tbody></table>
       

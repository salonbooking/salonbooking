<?php
$showPrices = ($plugin->getSettings()->get('hide_prices') != '1')? true : false;
?>
<table width="502" border="0" align="left" cellpadding="0" cellspacing="0">
          <tbody><tr>
            <td width="157" align="center" valign="top" style="border-right:2px solid #f2f2f2;"><table width="112" border="0" align="center" cellpadding="0" cellspacing="0">
              <tbody><tr>
                <td align="left" valign="top">&nbsp;</td>
              </tr>
              <tr>
                <td height="25" align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#cc3333; font-weight:normal;"><?php _e('Date', 'sln')?></td>
              </tr>
              <tr>
                <td height="36" align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:18px; color:#666666; font-weight:bold;"><?php echo $plugin->format()->date($booking->getDate()); ?></td>
              </tr>
              <tr>
                <td height="25" align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#cc3333; font-weight:normal;"><?php echo _e('Time','sln') ?></td>
              </tr>
              <tr>
                <td height="25" align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:18px; color:#666666; font-weight:bold;"> <?php echo $plugin->format()->time($booking->getTime()) ?></td>
              </tr>
    <?php if($attendant = $booking->getAttendant()) :  ?>
              <tr>
                <td height="25" align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#cc3333; font-weight:normal;"><?php echo _e('Attendant','sln') ?></td>
              </tr>
              <tr>
                <td height="25" align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:18px; color:#666666; font-weight:bold;"> <?php echo $attendant->getName() ?></td>
              </tr>
   <?php endif ?>
 
              <tr>
                <td align="left" valign="top">&nbsp;</td>
              </tr>
            </tbody></table></td>
            <td width="194" align="center" valign="top" style="border-right:2px solid #f2f2f2;"><table width="155" border="0" cellspacing="0" cellpadding="0">
              <tbody><tr>
                <td align="left" valign="top">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#cc3333; font-weight:normal;"><?php _e('Services') ?></td>
              </tr>
                                    <?php foreach ($plugin->getServices() as $service) : ?>
                                        <?php if ($booking->hasService($service)): ?>
                                        <tr>
                                            <td height="20" align="left" valign="top"
                                                style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#666666; font-weight:bold;"><?php echo $service->getName(
                                                ); ?></td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php endforeach; ?>
             <tr>
                <td align="left" valign="top">&nbsp;</td>
              </tr>
            </tbody></table></td>
            <td width="147" align="center" valign="top"><table width="112" border="0" align="center" cellpadding="0" cellspacing="0">
              <tbody><tr>
                <td align="left" valign="top">&nbsp;</td>
              </tr>
              <tr>
                <td height="25" align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#cc3333; font-weight:normal;"><?php if($showPrices){?><?php _e('Total amount', 'sln') ?><?php } ?></td>
              </tr>
              <tr>
                <td height="36" align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:18px; color:#666666; font-weight:bold;"><?php if($showPrices){?><?php echo $plugin->format()->money($booking->getAmount()) ?><?php } ?></td>
              </tr>
              <tr>
                <td height="28" align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#cc3333; font-weight:normal;"><?php _e('Status','sln')?></td>
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
       

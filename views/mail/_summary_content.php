<?php

if(!isset($forAdmin)) {
    $forAdmin = false;
}
?>
<tr>
    <td align="center" valign="top" bgcolor="#f2f2f2">
        <table width="502" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
                <td height="25">&nbsp;</td>
            </tr>
            <tr>
                <td align="left" valign="top"
                    style="font-family:Arial, Helvetica, sans-serif; font-size:16px; color:#666666; font-weight:normal;">
                   
<?php /*
                    <?php _e('Dear', 'salon-booking-system') ?>

                    <?php if($forAdmin): ?>
                    <?php _e('Administrator','salon-booking-system') ?>
                <?php else: ?>
                    <?php echo esc_attr($booking->getFirstname()) . ' ' . esc_attr($booking->getLastname()); ?>,
                <?php endif; ?>
*/?>
                </td>
            </tr>
            <tr>
                <td align="left" valign="top">&nbsp;</td>
            </tr>
            <tr>
                <td align="left" valign="top"
                    style="font-family:Arial, Helvetica, sans-serif; font-size:16px; color:#666666; font-weight:normal;">
                    
                    

                
<?php if ($plugin->getSettings()->get('confirmation') && $booking->hasStatus(SLN_Enum_BookingStatus::PENDING) ) : ?>

  <?php if($forAdmin): ?>

         <a  style="text-decoration:none;" href="<?php echo admin_url() ?>/post.php?post=<?php echo $booking->getId() ?>&action=edit">
             <?php echo __('Click here to approve ', 'salon-booking-system') ?>
             <?php echo esc_attr($booking->getFirstname()) . ' ' . esc_attr($booking->getLastname()); ?>
             <?php echo __('booking request.', 'salon-booking-system') ?>
         </a>

  <?php else: ?>

        <p><?php echo __('Your booking is pending, please await our confirmation.','salon-booking-system') ?></p>

  <?php endif ?>

<?php elseif(isset($updated) && $updated): ?>

    <?php
    if($forAdmin) {
        $updated_message = __('Reservation at [SALON NAME] has been modified', 'salon-booking-system');
    }
    else{
        $updated_message = isset($updated_message) && !empty($updated_message) ? $updated_message : $plugin->getSettings()->get('booking_update_message');
    }

    $updated_message = str_replace(
        array('[NAME]', '[SALON NAME]'),
        array(
            ($customer = $booking->getCustomer()) ? $customer->getName() : '',
            $plugin->getSettings()->get('gen_name') ? $plugin->getSettings()->get('gen_name') : get_bloginfo('name'),
        ),
        $updated_message
    );
    ?>

    <p><?php echo $updated_message ?></p>

    <b style="color:#666666;">
    <?php echo $plugin->getSettings()->get('gen_name') ?
                $plugin->getSettings()->get('gen_name') : get_bloginfo('name') ?>.</b>
    <br>

<?php else: ?>

  <?php if($forAdmin): ?>

      <?php echo __('This is an e-mail notification of a new booking', 'salon-booking-system') ?>
      <p><?php _e('Please take note of the following booking details.', 'salon-booking-system') ?></p>

  <?php else: ?>

      <?php if(isset($remind) && $remind): ?>

            <?php echo __('Remind your booking at', 'salon-booking-system') ?>

            <b style="color:#666666;">
            <?php echo $plugin->getSettings()->get('gen_name') ?
                        $plugin->getSettings()->get('gen_name') : get_bloginfo('name') ?>.</b>
            <br>
            <p><?php _e('Please take note of the following booking details.', 'salon-booking-system') ?></p>

        <?php else: ?>

            <?php echo __('This is an e-mail confirmation of your booking at', 'salon-booking-system') ?>

            <b style="color:#666666;">
            <?php echo $plugin->getSettings()->get('gen_name') ?
                        $plugin->getSettings()->get('gen_name') : get_bloginfo('name') ?>.</b><br></p>
            <p><?php _e('Please take note of the following booking details.', 'salon-booking-system') ?></p>

        <?php endif ?>

    <?php endif ?>

<?php endif ?>
                </td>
            </tr>
            <tr>
                <td align="left" valign="top">&nbsp;</td>
            </tr>
            <tr>
                <td align="left" valign="top" bgcolor="#ffffff">
                    <?php echo $plugin->loadView('mail/_summary_details',compact('booking')) ?>
                </td>
            </tr>
            <?php if($booking->hasStatus(SLN_Enum_BookingStatus::PENDING_PAYMENT)){ ?>
            <tr>
                <td align="left" valign="top" bgcolor="#ffffff">
                    <?php include('_summary_pendingpayment.php') ?>
                </td>
            </tr>
            <?php } ?>
            <tr>
                <td height="25" align="left" valign="top">&nbsp;</td>
            </tr>
            <?php
            if(!$forAdmin || !SLN_Enum_CheckoutFields::isHidden('address')) {
                if (!$forAdmin) {
                    $title = __('Our address', 'salon-booking-system');
                    $text = $plugin->getSettings()->get('gen_address');
                }
                else {
                    $title = __('Customer address', 'salon-booking-system');
                    $text = $booking->getAddress();
                }
                ?>
                <tr>
                    <td align="center" valign="top"><table width="460" border="0" align="center" cellpadding="0" cellspacing="0">
                        <tbody><tr>
                            <td width="242" align="left" valign="middle" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#cc3333; font-weight:normal;"><?php echo $title ?></td>
                            <td align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:16px; color:#666666; font-weight:normal;"><?php echo $text ?></td>
                        </tr>
                        </tbody></table></td>
                </tr>
                <tr>
                    <td height="25" align="left" valign="top">&nbsp;</td>
                </tr>
            <?php } ?>

<tr>
        <td align="center" valign="top" bgcolor="#ffffff"><table width="460" border="0" align="center" cellpadding="0" cellspacing="0">
          <tbody><tr>
              <?php
              if($forAdmin) {
                  $title = __('Customer contacts', 'salon-booking-system');
                  $firstnameHidden = SLN_Enum_CheckoutFields::isHidden('firstname');
                  $lastnameHidden  = SLN_Enum_CheckoutFields::isHidden('lastname');
                  $text = (!$firstnameHidden && !$lastnameHidden ?
                      $booking->getDisplayName() : (!$firstnameHidden ? $booking->getFirstname() : (!$lastnameHidden ? $booking->getLastname() : '')));
                  $m = $booking->getEmail();
                  $phone = (!SLN_Enum_CheckoutFields::isHidden('phone') ? $booking->getPhone() : '');
              }
              else {
                  $title = __('Contacts', 'salon-booking-system');
                  $text = '';
                  $m = $plugin->getSettings()->getSalonEmail();
                  $phone = $plugin->getSettings()->get('gen_phone');
              }
              ?>
            <td width="242" align="left" valign="middle" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#cc3333; font-weight:normal;"><?php echo $title ?></td>
            <td align="left" valign="top"><table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
              <tbody><tr>
                <td height="27">&nbsp;</td>
              </tr>
              <tr>
                  <td align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:16px; color:#666666; font-weight:normal;"><?php echo $text ?></td>
              </tr>
              <tr>
                <td align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:16px; font-weight:normal;">
                                <a href="mailto:<?php echo $m ?>"
                                   style="color:#666666; text-decoration:none;"><?php echo $m ?></a></td>
              </tr>
              <tr>
                <td height="22">&nbsp;</td>
              </tr>
              <tr>
                <td align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:16px; color:#666666; font-weight:normal;"><?php echo $phone ?></td>
              </tr>
              <tr>
                <td height="35">&nbsp;</td>
              </tr>
            </tbody></table></td>
          </tr>
        </tbody></table>
    </td>
</tr>

              <?php 
                $additional_fields = SLN_Enum_CheckoutFields::toArray('additional');
                if($additional_fields){
                 ?>
                  <tr>
                    <td height="22">&nbsp;</td>
                  </tr>
                  <tr>
        <td align="center" valign="top" bgcolor="#ffffff"><table width="460" border="0" align="center" cellpadding="0" cellspacing="0">
          <tbody>
            <tr>
                <td height="27">&nbsp;</td>
              </tr>
                 <?php 
                 foreach ($additional_fields as $field => $label) {
                  $value = $booking->getMeta($field);
                  if(!$forAdmin && (SLN_Enum_CheckoutFields::isHidden($field) || empty($value) )) continue;
                  ?>
                  <tr>
                    <td align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#cc3333; font-weight:normal;"><?php echo $label ?></td>
                    <td align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:16px; color:#666666; font-weight:normal;"><?php echo $value ?></td>
                  </tr>
                  <tr>
                    <td height="22">&nbsp;</td>
                  </tr>
                  <?php

                 }
                 ?>
                 </tbody></table></td></tr>
              <?php } ?> 
            
<tr>
    <td height="40" align="left" valign="top">&nbsp;</td>
</tr>
<tr>
    <td align="center" valign="top">
        <table width="460" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
                <td height="24" align="left" valign="top"
                    style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#666666; font-weight:bold;">
                    <?php _e('Important notes', 'salon-booking-system') ?></td>
            </tr>
            <tr>
                <td align="left" valign="top"
                    style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#666666; font-weight:normal;">
                    <?php echo $plugin->getSettings()->get('gen_timetable') ?>
                </td>
            </tr>
            <tr>
                <td height="40" align="left" valign="top">&nbsp;</td>
            </tr>
        </table>
    </td>
</tr>
<?php if($forAdmin): ?>
<tr>
    <td align="center" valign="top">
        <table width="460" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
                <td height="24" align="left" valign="top"
                    style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#666666; font-weight:bold;">
 
<p><?php _e('Customer message:', 'salon-booking-system')?>: <?php echo esc_attr($booking->getNote())?></p>
<?php if ($plugin->getSettings()->get('confirmation') && $booking->hasStatus(SLN_Enum_BookingStatus::PENDING)) : ?>
    <p><strong><?php _e('Please confirm or reject this booking from administration', 'salon-booking-system') ?></strong></p>
<?php endif ?>
<a href="<?php echo admin_url() ?>/post.php?post=<?php echo $booking->getId() ?>&action=edit">
    <?php _e('View this booking into administration.','salon-booking-system') ?></a>
    <p>&nbsp;</p>
                </td>
            </tr>
        </table>
    </td>
</tr>
<?php endif ?>
</table>
</td></tr>

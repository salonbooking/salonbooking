<?php
if(!function_exists('tpl_summary_details')){
    function tpl_summary_details($booking, $plugin){
        $showPrices = !$plugin->getSettings()->isHidePrices();
        ?>
        <table width="502" border="0" align="left" cellpadding="0" cellspacing="0">
            <tbody><tr>
                <td width="157" align="center" valign="top" style="border-right:2px solid #f2f2f2;"><table width="112" border="0" align="center" cellpadding="0" cellspacing="0">
                        <tbody><tr>
                            <td align="left" valign="top">&nbsp;</td>
                        </tr>
                        <tr>
                            <td height="25" align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#cc3333; font-weight:normal;"><?php _e('Date', 'salon-booking-system')?></td>
                        </tr>
                        <tr>
                            <td height="36" align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:18px; color:#666666; font-weight:bold;"><?php echo $plugin->format()->date($booking->getDate()); ?></td>
                        </tr>
                        <tr>
                            <td height="25" align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#cc3333; font-weight:normal;"><?php echo _e('Time','salon-booking-system') ?></td>
                        </tr>
                        <tr>
                            <td height="25" align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:18px; color:#666666; font-weight:bold;"> <?php echo $plugin->format()->time($booking->getTime()) ?></td>
                        </tr>
                        <?php if($attendant = $booking->getAttendant()) :  ?>
                            <tr>
                                <td height="25" align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#cc3333; font-weight:normal;"><?php echo _e('Attendant','salon-booking-system') ?></td>
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
                            </td>
                        </tr>
                        <tr>
                            <td align="left" valign="top">&nbsp;</td>
                        </tr>
                        </tbody></table></td>
            </tr>
            </tbody></table>
        <?php
    }}
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
                </td>
            </tr>
            <tr>
                <td align="left" valign="top">&nbsp;</td>
            </tr>
            <tr>
                <td align="left" valign="top"
                    style="font-family:Arial, Helvetica, sans-serif; font-size:16px; color:#666666; font-weight:normal;">
<p>
<?php echo __('Dear', 'salon-booking-system') . ' ' . $booking->getDisplayName() . ','; ?>
</p>

<p>
                        <?php echo __('the folowing booking at ' . $plugin->getSettings()->get('gen_name') . ' has been canceled', 'salon-booking-system') ?>.</p>
                </td>
            </tr>
            <tr>
                <td align="left" valign="top">&nbsp;</td>
            </tr>
            <tr>
                <td align="left" valign="top" bgcolor="#ffffff">
                    <?php tpl_summary_details($booking, $plugin) ?>
                </td>
            </tr>
            <tr>
                <td height="25" align="left" valign="top">&nbsp;</td>
            </tr>
            <tr>
                <td align="center" valign="top"><table width="460" border="0" align="center" cellpadding="0" cellspacing="0">
                        <tbody><tr>
                            <td width="242" align="left" valign="middle" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#cc3333; font-weight:normal;"><?php echo __('Our address', 'salon-booking-system') ?></td>
                            <td align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:16px; color:#666666; font-weight:normal;"><?php echo $plugin->getSettings()->get('gen_address') ?></td>
                        </tr>
                        </tbody></table></td>
            </tr>

            <tr>
                <td height="25" align="left" valign="top">&nbsp;</td>
            </tr>

            <tr>
                <td align="center" valign="top" bgcolor="#ffffff"><table width="460" border="0" align="center" cellpadding="0" cellspacing="0">
                        <tbody><tr>
                            <td width="242" align="left" valign="middle" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#cc3333; font-weight:normal;"><?php echo __('Contacts', 'salon-booking-system')?></td>
                            <td align="left" valign="top"><table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
                                    <tbody><tr>
                                        <td height="27">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:16px; font-weight:normal;">
                                            <?php $m = $plugin->getSettings()->get('gen_email') ?
                                                $plugin->getSettings()->get('gen_email') : get_bloginfo('admin_email');?>
                                            <a href="mailto:<?php echo $m ?>"
                                               style="color:#666666; text-decoration:none;"><?php echo $m ?></a></td>
                                    </tr>
                                    <tr>
                                        <td height="22">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:16px; color:#666666; font-weight:normal;"><?php echo $plugin->settings->get('gen_phone') ?></td>
                                    </tr>
                                    <tr>
                                        <td height="35">&nbsp;</td>
                                    </tr>
                                    </tbody></table></td>
                        </tr>
                        </tbody></table>
                </td>
            </tr>
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
        </table>
    </td></tr>

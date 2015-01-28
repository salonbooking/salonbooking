<tr>
    <td height="105" align="center" valign="middle" bgcolor="#f2f2f2" style="border-bottom:2px solid #fff;">
        <table width="191" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
                <td width="55" align="left" valign="top"><a href="#"><img src="images/img1.png" width="40" height="41"
                                                                          alt="img1" border="0" align="left"></a></td>
                <td align="left" valign="top">
                    <table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
                        <tr>
                            <td height="20" align="left" valign="bottom"
                                style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#cccccc; font-weight:bold;">
                                Booking ID <b style="color:#666666;"><?php echo $booking->getId() ?></b></td>
                        </tr>
                        <tr>
                            <td height="25" align="left" valign="bottom"
                                style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#cccccc; font-weight:bold;">
                                <?php _e('Status', 'sln') ?> <b
                                    style="color:#666666;"><?php SLN_Enum_BookingStatus::getLabel(
                                        $booking->getStatus()
                                    ) ?></b></td>
                        </tr>
                    </table>
                    <?php if ($plugin->settings->get('confirmation')) : ?>
                        <strong><?php _e('Please wait our confirmation') ?></strong></p>
                    <?php endif ?>
                </td>
            </tr>
        </table>
    </td>
</tr>
<tr>
    <td align="center" valign="top" bgcolor="#f2f2f2">
        <table width="502" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
                <td height="25">&nbsp;</td>
            </tr>
            <tr>
                <td align="left" valign="top"
                    style="font-family:Arial, Helvetica, sans-serif; font-size:16px; color:#666666; font-weight:normal;">
                    <?php _e('Dear', 'sln') ?>
                    <?php echo esc_attr($booking->getFirstname()) . ' ' . esc_attr($booking->getLastname()); ?>,
                </td>
            </tr>
            <tr>
                <td align="left" valign="top">&nbsp;</td>
            </tr>
            <tr>
                <td align="left" valign="top"
                    style="font-family:Arial, Helvetica, sans-serif; font-size:16px; color:#666666; font-weight:normal;">
                    <?php _e('this is an email confirmation of your booking at', 'sln') ?>
                    <b style="color:#666666;">
                        <?php echo $plugin->settings->get('gen_name') ?
                            $plugin->settings->get('gen_name') : get_bloginfo('name') ?>.</b><br>
                    <?php _e('Please take note of the following booking details.', 'sln') ?></td>
            </tr>
            <tr>
                <td align="left" valign="top">&nbsp;</td>
            </tr>
            <tr>
                <td align="left" valign="top" bgcolor="#ffffff">
                    <table width="502" border="0" align="left" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="157" align="center" valign="top" style="border-right:2px solid #f2f2f2;">
                                <table width="112" border="0" align="center" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td align="left" valign="top">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td height="25" align="left" valign="top"
                                            style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#cc3333; font-weight:normal;">
                                            <?php _e('Date','sln') ?></td>
                                    </tr>
                                    <tr>
                                        <td height="36" align="left" valign="top"
                                            style="font-family:Arial, Helvetica, sans-serif; font-size:18px; color:#666666; font-weight:bold;">
                                            <?php echo $plugin->format()->date($booking->getDate()); ?></td>
                                    </tr>
                                    <tr>
                                        <td height="25" align="left" valign="top"
                                            style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#cc3333; font-weight:normal;">
                                            <?php _e('Time', 'sln') ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top"
                                            style="font-family:Arial, Helvetica, sans-serif; font-size:18px; color:#666666; font-weight:bold;">
                                            <?php echo $plugin->format()->time($booking->getTime()) ?></td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top">&nbsp;</td>
                                    </tr>
                                </table>
                            </td>
                            <td width="194" align="center" valign="top" style="border-right:2px solid #f2f2f2;">
                                <table width="155" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td align="left" valign="top">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td height="30" align="left" valign="top"
                                            style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#cc3333; font-weight:normal;">
                                            <?php _e('Services','sln') ?></td>
                                    </tr>
                                    <?php foreach ($plugin->getServices() as $service) : ?>
                                        <? if ($booking->hasService($service): ?>
                                        <tr>
                                            <td height="20" align="left" valign="top"
                                                style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#666666; font-weight:bold;"><?php echo $service->getName(
                                                ); ?></td>
                                        </tr>
                                    <?php endif; endforeach ?>
                                    <tr>
                                        <td align="left" valign="top">&nbsp;</td>
                                    </tr>
                                </table>
                            </td>
                            <td width="147" align="center" valign="top">
                                <table width="112" border="0" align="center" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td align="left" valign="top">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td height="25" align="left" valign="top"
                                            style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#cc3333; font-weight:normal;">
                                            <?php _e('Total amount', 'sln') ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td height="36" align="left" valign="top"
                                            tyle="font-family:Arial, Helvetica, sans-serif; font-size:18px; color:#666666; font-weight:bold;">
                                            <?php echo $plugin->format()->money($booking->getAmount()) ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td height="28" align="left" valign="top"
                                            tyle="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#cc3333; font-weight:normal;">
                                            <?php _e('Status', 'sln') ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top"
                                            style="font-family:Arial, Helvetica, sans-serif; font-size:18px; color:#666666; font-weight:bold;">
                                            <?php SLN_Enum_BookingStatus::getLabel($booking->getStatus()) ?></td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top">&nbsp;</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                    <
                    /td>
            </tr>
            <tr>
                <td height="25" align="left" valign="top">&nbsp;</td>
            </tr>
            <tr>
                <td align="center" valign="top"><
                    table width="460" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
                <td width="242" align="left" valign="middle"
                    tyle="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#cc3333; font-weight:normal;">
                    Our address
                </td>
                <td align="left" valign="top"
                    style="font-family:Arial, Helvetica, sans-serif; font-size:16px; color:#666666; font-weight:normal;">
                    <?php echo $plugin->settings->get('confirmation') ?>
                </td>
            </tr>
        </table>
        <
        /td>
</tr>
<tr>
    <td height="25" align="left" valign="top">&nbsp;</td>
</tr>
<tr>
    <td align="center" valign="top" bgcolor="#ffffff">
        <table width="460" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
                <td width="242" align="left" valign="middle"
                    tyle="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#cc3333; font-weight:normal;">
                    Contacts
                </td>
                <td align="left" valign="top">
                    <table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
                        <tr>
                            <td height="27">&nbsp;</td>
                        </tr>
                        <tr>
                            <td align="left" valign="top"
                                style="font-family:Arial, Helvetica, sans-serif; font-size:16px; font-weight:normal;">
                                <?php $m = $plugin->settings->get('gen_mail') ?
                                    $plugin->settings->get('gen_name') : get_bloginfo('admin_email');?>
                                <a href="mailto:<?php echo $m ?>"
                                   tyle="color:#666666; text-decoration:none;"><?php echo $m ?></a>
                            </td>
                        </tr>
                        <tr>
                            <td height="22">&nbsp;</td>
                        </tr>
                        <tr>
                            <td align="left" valign="top"
                                tyle="font-family:Arial, Helvetica, sans-serif; font-size:16px; color:#666666; font-weight:normal;">
                                <?php echo $plugin->settings->get('gen_phone') ?></td>
                        </tr>
                        <tr>
                            <td height="35">&nbsp;</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
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
                    tyle="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#666666; font-weight:bold;">
                    <?php _e('Important notes', 'sln') ?></td>
            </tr>
            <tr>
                <td align="left" valign="top"
                    style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#666666; font-weight:normal;">
                    <?php echo $plugin->settings->get('gen_timetable') ?>
                </td>
            </tr>
            <tr>
                <td height="40" align="left" valign="top">&nbsp;</td>
            </tr>
        </table>
    </td>
</tr>
</table>
<?php if($forAdmin): ?>
<p><?php _e('Notes', 'sln'): <?php echo esc_attr($booking->getNote())?></p>
<?php if ($plugin->settings->get('confirmation')) : ?>
    <p><strong><?php _e('Please confirm or reject this booking from administration', 'sln') ?></strong></p>
<?php endif ?>
<a href="<?php echo admin_url() ?>/post.php?post=<?php echo $booking->getId() ?>&action=edit">
    <?php _e('View this booking in administration','sln') ?></a>
<?php endif ?>
</td>
</tr>
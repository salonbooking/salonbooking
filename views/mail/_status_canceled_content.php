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
                        <?php echo sprintf(__('the following booking at %s has been canceled', 'salon-booking-system'),$plugin->getSettings()->get('gen_name')) ?>.</p>
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
                                            <?php $m = $plugin->getSettings()->getSalonEmail();?>
                                            <a href="mailto:<?php echo $m ?>"
                                               style="color:#666666; text-decoration:none;"><?php echo $m ?></a></td>
                                    </tr>
                                    <tr>
                                        <td height="22">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:16px; color:#666666; font-weight:normal;"><?php echo $plugin->getSettings()->get('gen_phone') ?></td>
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

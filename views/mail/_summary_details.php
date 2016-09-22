<?php
$showPrices = !$plugin->getSettings()->isHidePrices();
/** @var SLN_Wrapper_Booking $booking */
$isMultipleAttSelection = $plugin->getSettings()->get('m_attendant_enabled');
$depositText = ($booking->getDeposit() && $booking->hasStatus(SLN_Enum_BookingStatus::PAID)) ?
    $plugin->format()->moneyFormatted($booking->getDeposit()) : null;
?>
<table width="502" border="0" align="left" cellpadding="0" cellspacing="0">
    <tbody>
    <tr>
        <td width="157" align="center" valign="top" style="border-right:2px solid #f2f2f2;">
            <table width="112" border="0" align="center" cellpadding="0" cellspacing="0">
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
                <?php if((!$isMultipleAttSelection) && $attendant = $booking->getAttendant()) :  ?>
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
                </tbody></table>
        </td>
        <td width="194" align="center" valign="top" style="border-right:2px solid #f2f2f2;">
<?php if ($isMultipleAttSelection) : ?>
    <table width="160" border="0" align="center" cellpadding="0" cellspacing="0">
        <tbody><tr>
            <td align="left" valign="top">&nbsp;</td>
        </tr>
        <tr>
            <td height="25" align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#cc3333; font-weight:normal;"><?php _e('Service','salon-booking-system') ?></td>
            <td height="25" align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#cc3333; font-weight:normal;"><?php _e('Attendant','salon-booking-system') ?></td>
        </tr>
        <?php $printDate = true; ?>
        <?php foreach($booking->getBookingServices()->getItems() as $bookingService): ?>
            <tr>
                <td height="20" align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#666666; font-weight:bold;"><?php echo $bookingService->getService()->getName(); ?></td>
                <td height="20" align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#666666; font-weight:bold;"><?php echo ($bookingService->getAttendant() ? $bookingService->getAttendant()->getName() : ''); ?></td>
            </tr>
        <?php endforeach ?>
        <tr>
            <td align="left" valign="top">&nbsp;</td>
        </tr>
        </tbody>
    </table>
<?php else: ?>
    <table width="155" border="0" cellspacing="0" cellpadding="0">
            <tbody><tr>
                <td align="left" valign="top">&nbsp;</td>
            </tr>
            <tr>
                <td height="30" align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#cc3333; font-weight:normal;"><?php _e('Services','salon-booking-system') ?></td>
            </tr>
            <?php foreach ($booking->getServices() as $service) : ?>
                    <tr>
                        <td height="20" align="left" valign="top"
                            style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#666666; font-weight:bold;"><?php echo $service->getName(); ?></td>
                    </tr>
            <?php endforeach; ?>
            <tr>
                <td align="left" valign="top">&nbsp;</td>
            </tr>
            </tbody></table>
<?php endif ?>
        </td>
        <td width="147" align="center" valign="top">
            <table width="112" border="0" align="center" cellpadding="0" cellspacing="0">
                <tbody>
                <tr>
                    <td align="left" valign="top">&nbsp;</td>
                </tr>
                <tr>
                    <td height="25" align="left" valign="top"
                        style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#cc3333; font-weight:normal;"><?php if ($showPrices) { ?><?php _e(
                            'Total amount',
                            'salon-booking-system'
                        ) ?><?php } ?></td>
                </tr>
                <tr>
                    <td height="36" align="left" valign="top"
                        style="font-family:Arial, Helvetica, sans-serif; font-size:18px; color:#666666; font-weight:bold;"><?php if ($showPrices) { ?><?php echo $plugin->format(
                        )->moneyFormatted($booking->getAmount()) ?><?php } ?></td>
                </tr>
                <tr>
                    <td height="28" align="left" valign="top"
                        style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#cc3333; font-weight:normal;"><?php _e(
                            'Status',
                            'salon-booking-system'
                        ) ?></td>
                </tr>
                <tr>
                    <td align="left" valign="top"
                        style="font-family:Arial, Helvetica, sans-serif; font-size:18px; color:#666666; font-weight:bold;">
                        <?php echo SLN_Enum_BookingStatus::getLabel($booking->getStatus()) ?>
                        <?php if ($depositText) { ?>
                            <span style="font-size: 14px; font-family: Arial, Helvetica, sans-serif; font-weight:normal;"><br/>Deposit <?php echo $depositText ?></span>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <td align="left" valign="top">&nbsp;</td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>

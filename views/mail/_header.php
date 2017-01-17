<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Untitled Document</title>
    <style type="text/css">
        body {
            margin-left: 0px;
            margin-top: 0px;
            margin-right: 0px;
            margin-bottom: 0px;
        }
    </style>
</head>

<body>
<table width="600" border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
    <?php $logo = $plugin->getSettings()->get('gen_logo'); ?>
    <td height="105" align="center" valign="middle" bgcolor="#f2f2f2" style="border-bottom:2px solid #fff;"><table <?php echo ($logo ? 'align="left"' : 'width="191" align="center"'); ?> border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td <?php echo ($logo ? 'width="259.5" align="center"' : 'width="55" align="left"'); ?> valign="top">
            <a href="#"><img src="<?php echo ($logo ? wp_get_attachment_image_url($logo, 'sln_gen_logo') : SLN_PLUGIN_URL.'/img/summary.png'); ?>" <?php echo ($logo ? '' : 'width="40"  height="41"') ?> alt="img1" border="0"></a>
        </td>
        <td align="left" valign="top"><table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
          <?php if(isset($booking)): ?>
              <tr>
                <td height="20" align="left" valign="bottom" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#cccccc; font-weight:bold;"><?php _e('Booking ID','salon-booking-system') ?> <b style="color:#666666;"><?php echo $booking->getId() ?></b></td>
              </tr>
              <tr>
                <td height="25" align="left" valign="bottom" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#cccccc; font-weight:bold;"><?php _e('Status','salon-booking-system')?> <b style="color:#666666;"><?php echo SLN_Enum_BookingStatus::getLabel(
                                            $booking->getStatus()
                                        ) ?></b></td>
              </tr>
          <?php else: ?>
              <tr>
                  <td height="20" align="left" valign="bottom" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#cccccc; font-weight:bold;"><b style="color:#666666;"></b></td>
              </tr>
              <tr>
                  <td height="25" align="left" valign="bottom" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#cccccc; font-weight:bold;"><b style="color:#666666;"><?php echo $plugin->getSettings()->getSalonName(); ?></b></td>
              </tr>
          <?php endif ?>
        </table></td>
      </tr>
    </table></td>
</tr>


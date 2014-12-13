<?php
/**
 * @var SLN_Plugin                $plugin
 * @var string                    $formAction
 * @var string                    $submitName
 * @var SLN_Shortcode_Saloon_Step $step
 */
$bb = $plugin->getBookingBuilder();
?>
<h1>When do you want to come?</h1>
<form method="post" action="<?php echo $formAction ?>">
    <?php SLN_Form::fieldDate('sln[date]', $bb->getDate()) ?>
    <?php SLN_Form::fieldTime('sln[time]', $bb->getTime()) ?>
    <button type="submit" class="btn btn-success" name="<?php echo $submitName ?>" value="next">Next</button>

</form>
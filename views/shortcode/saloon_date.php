<?php
/**
 * @var SLN_Plugin $plugin
 * @var string     $formAction
 * @var string     $submitName
 */
?>
<h1>Leave your data</h1>
<form method="post" action="<?php echo $formAction ?>">
    <?php SLN_Form::fieldDate('date') ?>
    <?php SLN_Form::fieldTime('time') ?>
    <input type="submit" name="<?php echo $submitName ?>" value="Next"/>
</form>
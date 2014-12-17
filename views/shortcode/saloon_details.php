<?php
/**
 * @var SLN_Plugin                $plugin
 * @var string                    $formAction
 * @var string                    $submitName
 * @var SLN_Shortcode_Saloon_Step $step
 */
$bb = $plugin->getBookingBuilder();
?>
<h2>Leave your data</h2>

<form method="post" action="<?php echo $formAction ?>" role="form">
    <?php foreach (array(
                       'firstname' => __('Firstname', 'sln'),
                       'lastname'  => __('Lastname', 'sln'),
                       'email'     => __('E-mail', 'sln'),
                       'phone'     => __('Phone', 'sln')
                   ) as $field => $label): ?>
        <div class="form-group">
            <label for="<?php echo SLN_Form::makeID('sln[' . $field . ']') ?>"><?php echo $label ?></label>
            <?php SLN_Form::fieldText('sln[' . $field . ']', $bb->get($field), array('required' => true)) ?>
        </div>
    <?php endforeach ?>
    <?php include "_form_actions.php" ?>
</form>

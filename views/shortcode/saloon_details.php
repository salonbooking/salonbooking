<?php
/**
 * @var SLN_Plugin                $plugin
 * @var string                    $formAction
 * @var string                    $submitName
 * @var SLN_Shortcode_Saloon_Step $step
 */
$bb = $plugin->getBookingBuilder();
?>
<div class="row">
    <div class="col-md-5">
        <h1>Leave your data</h1>

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
            <button type="submit" class="btn btn-success" name="<?php echo $submitName ?>" value="next">Next</button>
            <a class="btn btn-danger" href="<?php echo $backUrl ?> ">Back</a>
        </form>
    </div>
    <div class="col-md-2">
        OR
    </div>
    <div class="col-md-5">
        <h1>Login</h1>
    </div>
</div>

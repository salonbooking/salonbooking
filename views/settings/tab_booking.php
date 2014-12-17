<?php
/**
 * @param $this SLN_Admin_Settings
 */
function sln_availability_row($prefix, $row)
{
    ?>
    <div class="col-md-12">
        <?php foreach (SLN_Func::getDays() as $k => $day) : ?>
            <div class="form-group">
                <label>
                    <?php SLN_Form::fieldCheckbox(
                        $prefix . "[days][{$k}]",
                        $row['days'][$k]
                    ) ?>
                    <?php echo substr($day, 0, 3) ?></label>
            </div>
        <?php endforeach ?>
        <div class="pull-right">
            <?php foreach (array(0, 1) as $i) : ?>
                <?php foreach (array('from' => __('From', 'sln'), 'to' => __('To', 'sln')) as $k => $v) : ?>
                    <div class="form-group">
                        <label for="<?php echo SLN_Form::makeID($prefix . "[$k][$i]") ?>">
                            <?php echo $v ?>
                        </label>
                        <?php SLN_Form::fieldTime($prefix . "[$k][$i]", $row[$k][$i]) ?>
                    </div>
                <?php endforeach ?>
            <?php endforeach ?>
        </div>
    </div>
<?php
}

?>
<div class="sln-tab" id="sln-tab-booking">
    <div class="row form-inline">
        <div class="col-md-5">
            <div class="form-group">
                <label for="saloon_settings[parallels]">
                    <?php _e('How many people you can serve at the same time?', 'sln') ?>
                </label>
                <?php echo SLN_Form::fieldNumeric(
                    "saloon_settings[parallels]",
                    $this->getOpt('parallels'),
                    array('min' => 1, 'max' => 20)
                ) ?>
                <p class="help-block"><?php _e(
                        'Set this option carefully because will affect the number of bookings you can accept for the same da\/time.',
                        'sln'
                    ) ?></p>
            </div>
        </div>
        <?php
        $intervalItems = array(
            ''      => 'Always',
            'PT30M' => 'half hour',
            'PT1H'  => '1 hour',
            'PT2H'  => '2 hours',
            'PT3H'  => '3 hours',
            'PT4H'  => '4 hours',
            'PT8H'  => '8 hours',
            'PT16H' => '16 hours',
            'P1D'   => '1 day',
            'P2D'   => '2 days',
            'P3D'   => '3 days',
            'P4D'   => '4 days',
            'P1W'   => '1 week',
            'P2W'   => '2 weeks',
            'P3W'   => '3 weeks',
            'P1M'   => '1 month',
            'P2M'   => '2 months',
            'P3M'   => '3 months'
        )
        ?>
        <div class="col-md-6 offset-md-1">
            <div class="row">
                <div class="form-group">
                    <label>
                        <?php _e('How long before you can book?', 'sln') ?>
                    </label>
                </div>
                <div class="form-group">
                    <label for="<?php echo SLN_Form::makeID($field) ?> ">
                        <?php _e('From', 'sln') ?>
                    </label>
                    <?php $field = "saloon_settings[hours_before_from]"; ?>
                    <?php echo SLN_Form::fieldSelect($field, $intervalItems, $this->getOpt('hours_before_from')) ?>
                </div>
                <?php $field = "saloon_settings[hours_before_to]"; ?>
                <div class="form-group">
                    <label for="<?php echo SLN_Form::makeID($field) ?> ">
                        <?php _e('To', 'sln') ?>
                    </label>
                    <?php echo SLN_Form::fieldSelect($field, $intervalItems, $this->getOpt('hours_before_from')) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="sln-separator"></div>
    <?php
    $key            = 'available';
    $label          = __('Online booking not available on', 'sln');
    $availabilities = $this->getOpt('availabilities');
    ?>
    <div class="form-group">
        <label><?php echo $label ?></label>

        <p class="help-block">Leave blank if you want booking available everydays at every hour</p>
    </div>
    <div id="sln-availabilities">
        <div class="items">
            <?php foreach ($availabilities as $k => $row): ?>
                <div class="item">
                    <div class="row form-inline">
                        <div class="col-md-10">
                            <?php sln_availability_row("saloon_settings[availabilities][$k]", $row); ?>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-block btn-danger" data-collection="remove">
                                <i class="glyphicon glyphicon-minus"></i> Remove
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
        <div class="col-md-2 pull-right">
            <button data-collection="addnew" class="btn btn-block btn-primary"><i
                    class="glyphicon glyphicon-plus"></i> <?php _e(
                    'Add new'
                ) ?>
            </button>
        </div>
        <div data-collection="prototype" data-count="<?php echo count($availabilities) ?>">
            <div class="row form-inline">
                <div class="col-md-10">
                    <?php sln_availability_row("saloon_settings[availabilities][__new__]", $row); ?>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-block btn-danger" data-collection="remove">
                        <i class="glyphicon glyphicon-minus"></i> Remove
                    </button>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="sln-separator"></div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label><?php _e('Disable online booking', 'sln') ?>
                        <?php SLN_Form::fieldCheckbox("saloon_settings[disabled]", $this->getOpt('disabled_message')) ?>
                    </label>

                    <p class="help-block">
                        <?php _e(
                            'If checked the booking user will see a message with the reason of disabled booking.',
                            'sln'
                        ) ?></p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="<?php echo SLN_form::makeID("saloon_settings[disabled_message]") ?>"><?php _e(
                            'Message on disabled booking',
                            'sln'
                        ) ?></label>
                    <?php SLN_Form::fieldTextarea(
                        "saloon_settings[disabled_message]",
                        $this->getOpt('disabled_message'),
                        array(
                            'attrs' => array(
                                'placeholder' => 'Write a message',
                                'rows'        => 5
                            )
                        )
                    ) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="sln-separator"></div>
    <div class="row">
        <div class="col-md-6">
            <?php $this->row_input_checkbox(
                'confirmation',
                __('Bookings Confirmation', 'sln'),
                array('help' => __('Select this if you want to confirm every single booking'))
            ); ?>
        </div>
        <div class="col-md-6">
            <?php $this->row_input_page('thankyou', __('Thank you page', 'sln')); ?>
        </div>
    </div>
</div>
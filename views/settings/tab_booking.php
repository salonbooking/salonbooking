<?php
/**
 * @param $this SLN_Admin_Settings
 */
function sln_availability_row($prefix, $row)
{
    ?>
    <div class="col-md-12">
        <div class="first-row">
        <?php foreach (SLN_Func::getDays() as $k => $day) : ?>
            <div class="form-group">
                <label>
                    <?php SLN_Form::fieldCheckbox(
                        $prefix . "[days][{$k}]",
                        (isset($row['days'][$k]) ? 1 : null)
                    ) ?>
                    <?php echo substr($day, 0, 3) ?></label>
            </div>
        <?php endforeach ?>
        </div>
        <div class="second-row">
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
    <div class="sln-box sln-box--main">
    <h2 class="sln-box-title">Availability mode <span>Select your availabiliti mode</span></h2>
    <div class="row">
        <div class="col-xs-12 col-sm-8">
            <div class="sln-radiobox">
                <input id="salon_settings_availability_mode--basic"  type="radio" name="salon_settings_availability_mode" value="basic">
                <label for="salon_settings_availability_mode--basic">Basic (check only the booking date)</label>
                <input id="salon_settings_availability_mode--advanced" type="radio" name="salon_settings_availability_mode" value="advanced" checked="checked">
                <label for="salon_settings_availability_mode--advanced">Advanced (evaluate also booking duration)</label>
            </div>
        </div>
        <div class="col-xs-12 col-sm-4 form-group sln-box-maininfo align-top">
            <p class="sln-input-help">Sed eget metus vitae enim suscipit scelerisque non sed neque. Mauris semper hendrerit erat, in consectetur arcu eleifend at. Donec orci lacus, euismod euismod luctus sed, rhoncus in tellus. Mauris tempus arcu ut luctus venenatis.</p>
        </div>
    </div>
    </div>
<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-6">
    <div class="sln-box sln-box--main">
    <h2 class="sln-box-title">Customers per session <span class="block">How many people you can attend during a single time/session?</span></h2>
    <div class="row">
            <div class="col-xs-12 form-group sln-select  sln-select--info-label">
            <!--<label for="salon_settings_parallels_hour">Customers per time/session</label>-->
            <div class="row">
            <div class="col-xs-4">
            <select name="salon_settings[parallels_hour]" id="salon_settings_parallels_hour" class="form-control">
                            <option value="1" selected="selected">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                            <option value="13">13</option>
                            <option value="14">14</option>
                            <option value="15">15</option>
                            <option value="16">16</option>
                            <option value="17">17</option>
                            <option value="18">18</option>
                            <option value="19">19</option>
                            <option value="20">20</option>
                    </select>
            </div>
            <div class="col-xs-8 sln-label--big"><label for="salon_settings_sms_remind_interval">Customers per session</label></div>
            </div>
        </div>
        </div>
    </div>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-6">
    <div class="sln-box sln-box--main">
    <h2 class="sln-box-title">Session average duration <span class="block">How many people you can attend during a single time/session?</span></h2>
    <div class="row">
            <div class="col-xs-12 form-group sln-select  sln-select--info-label">
            <!--<label for="salon_settings_parallels_hour">Customers per time/session</label>-->
            <div class="row">
            <div class="col-xs-4">
            <select name="salon_settings[parallels_hour]" id="salon_settings_parallels_hour" class="form-control">
                            <option value="1" selected="selected">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                            <option value="13">13</option>
                            <option value="14">14</option>
                            <option value="15">15</option>
                            <option value="16">16</option>
                            <option value="17">17</option>
                            <option value="18">18</option>
                            <option value="19">19</option>
                            <option value="20">20</option>
                    </select>
            </div>
            <div class="col-xs-8 sln-label--big"><label for="salon_settings_sms_remind_interval">Customers per session</label></div>
            </div>
        </div>
        </div>
    </div>
    </div>
</div>
</div>


<!--
<div class="sln-tab" id="sln-tab-booking">
    <div class="row form-inline">
        <div class="col-md-6">
            <div class="form-group">
                <label for="salon_settings[parallels]">
                    <?php _e('How many people you can attend during a single time/session?', 'sln') ?>
                </label>
                <?php echo SLN_Form::fieldNumeric(
                    "salon_settings[parallels_hour]",
                    $this->getOpt('parallels_hour'),
                    array('min' => 1, 'max' => 20)
                ) ?>
            </div>
<?php /*
            <div class="form-group">
                <label for="salon_settings[parallels]">
                    <?php _e('How many people you can serve at the same day?', 'sln') ?>
                </label>
                <?php echo SLN_Form::fieldNumeric(
                    "salon_settings[parallels_day]",
                    $this->getOpt('parallels_day'),
                    array('min' => 0, 'max' => 100)
                ) ?>
                <p class="help-block"><?php _e(
                        'Set this option carefully because will affect the number of bookings you can accept for the same <strong>day</strong>.<br/>Leave 0 to disable this limit',
                        'sln'
                    ) ?></p>
            </div>
*/?>

            <?php $field = "salon_settings[interval]"; ?>
                <label for="<?php echo SLN_Form::makeID($field) ?> ">
                    <?php _e('Define your time-session average duration', 'sln') ?>
                </label>
                <?php echo SLN_Form::fieldSelect(
                    $field,
                    array('5', '10', '15', '30', '60'),
                    $this->getOpt('interval') ? $this->getOpt('interval') : 15
                ) ?>
                
                <p class="help-block"><?php _e(
                        'Set these options carefully because it will affect the number of bookings you can accept for the same <strong>time/session</strong>.',
                        'sln'
                    ) ?></p>
            <label>        <?php _e('Select your availability mode', 'sln') ?></label>
            <?php $field = "salon_settings[availability_mode]"; ?>
            <?php echo SLN_Form::fieldSelect(
                $field,
                SLN_Enum_AvailabilityModeProvider::toArray(),
                $this->getOpt('availability_mode'),
                array(),
                true
            ) ?>

        </div>
        <div class="col-md-6"></div>
            <div class="clearfix"></div>
            <div class="sln-separator"></div>

    <div class="row settings-allowed">
        <div class="col-md-5">
                <div class="form-group">
                    <strong> 
                        <?php _e('Bookings are allowed from', 'sln') ?>
                    <?php $field = "salon_settings[hours_before_from]"; ?>
                    <?php echo SLN_Form::fieldSelect(
                        $field,
                        SLN_Func::getIntervalItems(),
                        $this->getOpt('hours_before_from'),
                        array(),
                        true
                    ) ?>
                   </strong>
            <!-- form-group END
            </div> -->
        <!-- col end END
        </div> -->
    <!--
        <div class="col-md-5">
                <div class="form-group">
                    <strong>

                        <?php _e('to a maximum of', 'sln') ?>

                    <?php $field = "salon_settings[hours_before_to]"; ?>
                    <?php echo SLN_Form::fieldSelect(
                        $field,
                        SLN_Func::getIntervalItems(),
                        $this->getOpt('hours_before_to'),
                        array(),
                        true
                    ) ?>
 
                        <?php _e('in advance', 'sln') ?>
                   </strong>
                   </strong>
            <!-- form-group END
            </div> -->
        <!-- col end END
        </div> -->
    <!-- row end END
    </div> -->
    <!--
    <div class="row setting-interval">
        <div class="col-md-8">

        </div>
    </div>
            <div class="clearfix"></div>
        <div class="sln-separator"></div>
        <?php
        $key            = 'available';
        $label          = __('On-line booking available days', 'sln');
        $availabilities = $this->getOpt('availabilities');
        ?>
        <div class="form-group">
            <label><?php echo $label ?></label>

            <p class="help-block"><?php _e('The following rules, should represent your real timetable. <br />Leave blank if you want bookings available everydays at every hour', 'sln') ?></p>
        </div>
        <div id="sln-availabilities">
            <div class="items">
                <?php foreach ($availabilities as $k => $row): ?>
                    <div class="item">
                        <div class="row form-inline">
                            <div class="col-md-10 checkbox-group">
                                <?php sln_availability_row("salon_settings[availabilities][$k]", $row); ?>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-block btn-danger" data-collection="remove">
                                    <i class="glyphicon glyphicon-minus"></i> <?php echo __('Remove', 'sln')?>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>
            <div class="col-md-2 pull-right">
                <button data-collection="addnew" class="btn btn-block btn-primary"><i
                        class="glyphicon glyphicon-plus"></i> <?php _e(
                        'Add new','sln'
                    ) ?>
                </button>
            </div>
            <div data-collection="prototype" data-count="<?php echo count($availabilities) ?>">
                <div class="row form-inline">
                    <div class="col-md-10">
                        <?php sln_availability_row("salon_settings[availabilities][__new__]", $row); ?>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-block btn-danger" data-collection="remove">
                            <i class="glyphicon glyphicon-minus"></i> <?php echo __('Remove', 'sln')?>
                        </button>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="sln-separator"></div>
            <div class="row settings-disable">
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php _e('Disable online booking', 'sln') ?>
                            <?php SLN_Form::fieldCheckbox(
                                "salon_settings[disabled]",
                                $this->getOpt('disabled')
                            ) ?>
                        </label>

                        <p class="help-block">
                            <?php _e(
                                'If checked the online booking form will be disabled and your users will see a message.',
                                'sln'
                            ) ?></p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="<?php echo SLN_form::makeID("salon_settings[disabled_message]") ?>"><?php _e(
                                'Message on disabled booking',
                                'sln'
                            ) ?></label>
                        <?php SLN_Form::fieldTextarea(
                            "salon_settings[disabled_message]",
                            $this->getOpt('disabled_message'),
                            array(
                                'attrs' => array(
                                    'placeholder' => 'Write a message',
                                    'rows'        => 5,
                                    'class'       => 'form-control',
                                    'style'       => 'width: 100%;'
                                )
                            )
                        ) ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="sln-separator"></div>
        <div class="row settings-confirmation">
            <div class="col-md-6">
                <?php $this->row_input_checkbox(
                    'confirmation',
                    __('Booking confirmation', 'sln'),
                    array('help' => __('Select this option to manually confirm each booking.','sln'))
                ); ?>
            </div>
            <div class="col-md-6">
                <?php $this->row_input_page('thankyou', __('Thank you page', 'sln')); ?>
                <p><?php _e('Select a page where to redirect your users after booking completition.', 'sln') ?></p>
            </div>
        </div>
    </div>
    -->

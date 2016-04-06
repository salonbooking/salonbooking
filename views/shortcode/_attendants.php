<?php
/**
 * @var SLN_Plugin                        $plugin
 * @var string                            $formAction
 * @var string                            $submitName
 * @var SLN_Shortcode_Salon_AttendantStep $step
 * @var SLN_Wrapper_Attendant[]           $attendants
 */

$ah = $plugin->getAvailabilityHelper();
$ah->setDate($plugin->getBookingBuilder()->getDateTime());
$duration = new SLN_DateTime('1970-01-01 '.$bb->getDuration());
$hasAttendants = false;
$style = $step->getShortcode()->getStyleShortcode();
$size = SLN_Enum_ShortcodeStyle::getSize($style);
?>
<div class="sln-attendant-list">
    <?php foreach ($attendants as $attendant) : ?>
        <?php
            $validateAttServicesErrors = $ah->validateAttendantServices($attendant, $bb->getServices());
            if(!empty($validateAttServicesErrors)) {
                continue;
            }
        ?>
    <?php
    if ($size == '900') { ?>
        <div class="row sln-attendant">
        <div class="col-xs-1 col-sm-1 sln-radiobox sln-steps-check sln-attendant-check <?php echo  $bb->hasAttendant($attendant) ? 'is-checked' : '' ?>">
            <?php
            $validateErrors            = $ah->validateAttendant($attendant, $bb->getDateTime(), $duration);
            if ($validateErrors) {
                $errors = $validateErrors;
            }
            else {
                $errors = false;
            }

            $settings = array();
            if ($errors) {
                $settings['attrs']['disabled'] = 'disabled';
            }
            ?>

            <?php SLN_Form::fieldRadioboxForGroup(
                'sln[attendants][]',
                'sln[attendant]',
                $attendant->getId(),
                $bb->hasAttendant($attendant),
                $settings
            ) ?>
        <!-- .sln-attendant-check // END -->
        </div>
        <div class="col-xs-4 col-sm-3 col-md-3 sln-steps-thumb sln-attendant-thumb">
            <?php
            if ( has_post_thumbnail($attendant->getId())) {
                echo get_the_post_thumbnail($attendant->getId(), 'thumbnail');
            }
            ?>
        </div>
        <div class="col-sm-8 col-md-8">
            <div class="row sln-steps-info sln-attendant-info">
                <div class="col-md-12">
                    <label for="<?php echo SLN_Form::makeID('sln[attendant][' . $attendant->getId() . ']') ?>">
                        <h3 class="sln-steps-name sln-attendant-name"><?php echo $attendant->getName(); ?></h3>
                    </label>
                <!-- .sln-attendant-info // END -->
                </div>
            </div>
            <div class="row sln-steps-description sln-attendant-description">
                    <div class="col-md-12">
                        <label for="<?php echo SLN_Form::makeID('sln[attendant][' . $attendant->getId() . ']') ?>">
                            <p><?php echo $attendant->getContent() ?></p>
                        </label>
                    <!-- .sln-attendant-info // END -->
                    </div>
            </div>
        </div>
        <?php if ($errors) : ?>
            <div class="col-md-12 alert alert-warning">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo $error ?></p>
                <?php endforeach ?>
            </div>
        <?php endif ?>
        <div class="clearfix"></div>
        <div class="col-md-12"><hr></div>
    </div>
    <?php
    // IF SIZE 900 // END
    } else if ($size == '600') { ?>
    <div class="row sln-attendant">
        <div class="col-xs-2 col-sm-1 sln-radiobox sln-steps-check sln-attendant-check <?php echo  $bb->hasAttendant($attendant) ? 'is-checked' : '' ?>">
            <?php
            $validateErrors            = $ah->validateAttendant($attendant, $bb->getDateTime(), $duration);
            if ( $validateErrors && $validateAttServicesErrors) {
                $errors = array_merge($validateErrors, $validateAttServicesErrors);
            }
            elseif ($validateErrors) {
                $errors = $validateErrors;
            }
            elseif ($validateAttServicesErrors) {
                $errors = $validateAttServicesErrors;
            }
            else {
                $errors = false;
            }

            $settings = array();
            if ($errors) {
                $settings['attrs']['disabled'] = 'disabled';
            }
            ?>

            <?php SLN_Form::fieldRadioboxForGroup(
                'sln[attendants][]',
                'sln[attendant]',
                $attendant->getId(),
                $bb->hasAttendant($attendant),
                $settings
            ) ?>
        <!-- .sln-attendant-check // END -->
        </div>
        <div class="col-xs-4 col-sm-3 col-md-3 sln-steps-thumb sln-attendant-thumb">
            <?php
            if ( has_post_thumbnail($attendant->getId())) {
                echo get_the_post_thumbnail($attendant->getId(), 'thumbnail');
            }
            ?>
        </div>
        <div class="col-xs-6 visible-xs-block">
            <label for="<?php echo SLN_Form::makeID('sln[attendant][' . $attendant->getId() . ']') ?>">
                <h3 class="sln-steps-name sln-attendant-name"><?php echo $attendant->getName(); ?></h3>
            </label>
        <!-- .sln-attendant-info // END -->
        </div>
        <div class="col-xs-12 col-sm-8 col-md-8">
            <div class="row sln-steps-info sln-attendant-info">
                <div class="col-md-12 hidden-xs">
                    <label for="<?php echo SLN_Form::makeID('sln[attendant][' . $attendant->getId() . ']') ?>">
                        <h3 class="sln-steps-name sln-attendant-name"><?php echo $attendant->getName(); ?></h3>
                    </label>
                <!-- .sln-attendant-info // END -->
                </div>
            </div>
            <div class="row sln-steps-description sln-attendant-description">
                    <div class="col-md-12">
                        <label for="<?php echo SLN_Form::makeID('sln[attendant][' . $attendant->getId() . ']') ?>">
                            <p><?php echo $attendant->getContent() ?></p>
                        </label>
                    <!-- .sln-attendant-info // END -->
                    </div>
            </div>
        </div>
        <?php if ($attendantErrors) : ?>
            <div class="col-md-12 alert alert-warning">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo $error ?></p>
                <?php endforeach ?>
            </div>
        <?php endif ?>
        <div class="clearfix"></div>
        <div class="col-md-12"><hr></div>
    </div>
    <?php
    // IF SIZE 600 // END
    } else if ($size == '400') { ?>
    <div class="row sln-attendant">
        <div class="col-xs-2 col-sm-2 sln-radiobox sln-steps-check sln-attendant-check <?php echo  $bb->hasAttendant($attendant) ? 'is-checked' : '' ?>">
            <?php
            $validateErrors            = $ah->validateAttendant($attendant, $bb->getDateTime(), $duration);
            if ( $validateErrors && $validateAttServicesErrors) {
                $errors = array_merge($validateErrors, $validateAttServicesErrors);
            }
            elseif ($validateErrors) {
                $errors = $validateErrors;
            }
            elseif ($validateAttServicesErrors) {
                $errors = $validateAttServicesErrors;
            }
            else {
                $errors = false;
            }

            $settings = array();
            if ($errors) {
                $settings['attrs']['disabled'] = 'disabled';
            }
            ?>

            <?php SLN_Form::fieldRadioboxForGroup(
                'sln[attendants][]',
                'sln[attendant]',
                $attendant->getId(),
                $bb->hasAttendant($attendant),
                $settings
            ) ?>
        <!-- .sln-attendant-check // END -->
        </div>
        <div class="col-xs-10 col-sm-10">
            <div class="row sln-steps-info sln-attendant-info">
                <div class="col-sm-4 col-xs-6 sln-steps-thumb sln-attendant-thumb">
                    <?php
                    if ( has_post_thumbnail($attendant->getId())) {
                        echo get_the_post_thumbnail($attendant->getId(), 'thumbnail');
                    }
                    ?>
                </div>
                <div class="col-sm-7 col-xs-6">
                    <label for="<?php echo SLN_Form::makeID('sln[attendant][' . $attendant->getId() . ']') ?>">
                        <h3 class="sln-steps-name sln-attendant-name"><?php echo $attendant->getName(); ?></h3>
                    </label>
                <!-- .sln-attendant-info // END -->
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="col-md-12 sln-steps-description sln-attendant-description">
                        <label for="<?php echo SLN_Form::makeID('sln[attendant][' . $attendant->getId() . ']') ?>">
                            <p><?php echo $attendant->getContent() ?></p>
                        </label>
            </div>
        <div class="clearfix"></div>
        <?php if ($errors) : ?>
            <div><div class="col-xs-offset-2 col-lg-offset-1"><div class="alert alert-danger alert-no-spacing">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo $error ?></p>
                <?php endforeach ?>
            </div></div></div>
        <?php endif ?>
        <div class="clearfix"></div>
        <div class="col-md-12"><hr></div>
    </div>
    <?php
    // IF SIZE 400 // END
    } else  { ?>

    <?php
    // ELSE // END
    }  ?>
    <?php $hasAttendants = true ?>
    <?php endforeach ?>
    <?php if(!$hasAttendants) : ?>
        <div class="alert alert-warning">
            <p><?php echo __('No assistants available for the selected time/slot - please choose another one', 'salon-booking-system') ?></p>
        </div>
    <?php endif ?> 
</div>

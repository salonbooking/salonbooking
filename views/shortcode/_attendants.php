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
$hasAttendants = false;
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
    $size = $_SESSION["size"];
    if ($size == '900') { ?>
        <div class="row sln-attendant">
        <div class="col-sm-1 col-md-1 sln-radiobox sln-steps-check sln-attendant-check <?php echo  $bb->hasAttendant($attendant) ? 'is-checked' : '' ?>">
            <?php
            $validateErrors            = $ah->validateAttendant($attendant, $bb->getDuration());
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
    // IF SIZE 900 // END
    } else if ($size == '600') { ?>
    <div class="row sln-attendant">
        <div class="col-sm-1 col-md-1 sln-radiobox sln-steps-check sln-attendant-check <?php echo  $bb->hasAttendant($attendant) ? 'is-checked' : '' ?>">
            <?php
            $validateErrors            = $ah->validateAttendant($attendant, $bb->getDuration());
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
        <div class="col-md-1 sln-radiobox sln-steps-check sln-attendant-check <?php echo  $bb->hasAttendant($attendant) ? 'is-checked' : '' ?>">
            <?php
            $validateErrors            = $ah->validateAttendant($attendant, $bb->getDuration());
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
        <div class="col-xs-11">
            <div class="row sln-steps-info sln-attendant-info">
                <div class="col-xs-4 sln-steps-thumb sln-attendant-thumb">
                    <?php
                    if ( has_post_thumbnail($attendant->getId())) {
                        echo get_the_post_thumbnail($attendant->getId(), 'thumbnail');
                    }
                    ?>
                </div>
                <div class="col-xs-7">
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

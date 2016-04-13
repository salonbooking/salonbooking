<div class="row sln-attendant">
    <div class="col-xs-2 col-sm-2 sln-radiobox sln-steps-check sln-attendant-check <?php echo $bb->hasAttendant(
        $attendant
    ) ? 'is-checked' : '' ?>">

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
                if (has_post_thumbnail($attendant->getId())) {
                    echo get_the_post_thumbnail($attendant->getId(), 'thumbnail');
                }
                ?>
            </div>
            <div class="col-sm-7 col-xs-6">
                <label for="<?php echo SLN_Form::makeID('sln[attendant]['.$attendant->getId().']') ?>">
                    <h3 class="sln-steps-name sln-attendant-name"><?php echo $attendant->getName(); ?></h3>
                </label>
                <!-- .sln-attendant-info // END -->
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-md-12 sln-steps-description sln-attendant-description">
        <label for="<?php echo SLN_Form::makeID('sln[attendant]['.$attendant->getId().']') ?>">
            <p><?php echo $attendant->getContent() ?></p>
        </label>
    </div>
    <div class="clearfix"></div>

    <div>
        <div class="col-xs-offset-2 col-lg-offset-1">
            <span class="errors-area">
            <?php if ($errors) : ?>
                <div class="alert alert-danger alert-no-spacing">
                    <?php foreach ($errors as $error): ?>
                        <p><?php echo $error ?></p>
                    <?php endforeach ?>
                </div>
            <?php endif ?>
            </span>
        </div>
    </div>
    <div class="clearfix"></div>
</div>

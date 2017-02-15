<div class="row sln-attendant">
    <div
        class="col-xs-2 col-sm-2 sln-radiobox sln-steps-check sln-attendant-check <?php echo $isChecked ? 'is-checked' : '' ?>">
        <?php SLN_Form::fieldRadioboxForGroup($field, $field, $attendant->getId(), $isChecked, $settings) ?>
    </div>
    <div class="col-xs-10 col-sm-10">
        <div class="row sln-steps-info sln-attendant-info">
            <div class="col-sm-4 col-xs-6 sln-steps-thumb sln-attendant-thumb">
                <?php echo $thumb ?>
            </div>
            <div class="col-sm-7 col-xs-6">
                <label for="<?php echo $elemId ?>">
                    <h3 class="sln-steps-name sln-attendant-name"><?php echo $attendant->getName(); ?></h3>
                </label>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-md-12 sln-steps-description sln-attendant-description">
        <label for="<?php echo $elemId ?>">
            <p><?php echo $attendant->getContent() ?></p>
        </label>
    </div>
    <?php echo $tplErrors ?>
</div>
<div class="clearfix"></div>

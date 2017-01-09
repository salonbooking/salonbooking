<?php if ($size == '400') : ?>
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
<?php else: ?>
    <span class="errors-area">
    <?php if ($errors) : ?>
        <div class="col-md-12 alert alert-warning">
            <?php foreach ($errors as $error): ?>
                <p><?php echo $error ?></p>
            <?php endforeach ?>
        </div>
    <?php endif ?>
    </span>
    <div class="clearfix"></div>
    <div class="col-md-12">
        <hr>
    </div>
<?php endif ?>

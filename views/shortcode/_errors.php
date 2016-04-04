<?php if ($errors): ?>
<div class="row sln-box--main">
    <div class="cool-md-12">
        <?php foreach ($errors as $error): ?>
            <div class="sln-alert sln-alert--problem"><?php echo $error ?></div>
        <?php endforeach ?>
    </div>
</div>
<?php endif ?>
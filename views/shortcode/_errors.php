<?php if ($errors): ?>
<div class="row sln-box--main">
    <div class="alert alert-danger">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo $error ?></li>
            <?php endforeach ?>
        </ul>
    </div>
</div>
<?php endif ?>
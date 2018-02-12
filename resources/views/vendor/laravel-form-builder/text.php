<?php if ($showLabel && $showField): ?>
    <?php if ($options['wrapper'] !== false): ?>
    <div <?= $options['wrapperAttrs'] ?> >
    <?php endif; ?>
<?php endif; ?>

<?php if ($showLabel && $options['label'] !== false && $options['label_show']): ?>
    <?= Form::customLabel($name, $options['label'], $options['label_attr']) ?>
<?php endif; ?>

<?php if ($showField): ?>
    <?php if (isset($options['pre-addon']) || isset($options['post-addon'])): ?>
    <div class="input-group">
    <?php endif; ?>

    <?php if (isset($options['pre-addon'])): ?>
    <div class="input-group-addon"><?= $options['pre-addon'] ?></div>
    <?php endif; ?>

    <?= Form::input($type, $name, $options['value'], $options['attr']) ?>

    <?php if (isset($options['post-addon'])): ?>
        <div class="input-group-addon"><?= $options['post-addon'] ?></div>
    <?php endif; ?>

    <?php if (isset($options['pre-addon']) || isset($options['post-addon'])): ?>
    </div>

    <?php include 'help_block.php' ?>

    <?php endif; ?>
<?php endif; ?>

<?php include 'errors.php' ?>

<?php if ($showLabel && $showField): ?>
    <?php if ($options['wrapper'] !== false): ?>
    </div>
    <?php endif; ?>
<?php endif; ?>

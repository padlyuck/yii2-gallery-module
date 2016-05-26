<?php

use sadovojav\gallery\Module;
use yii\helpers\Html;

?>

<div class="image col-lg-3 col-md-4 col-sm-6 col-xs-12" data-id="<?= $model->id ?>">
    <div class="image-wrapper">
        <div class="handle">
            <img src="<?= $model->src; ?>" class="portrait img-responsive"/>
        </div>

        <div class="bottom">
            <?= Html::textInput('caption', $model->caption, [
                'class'       => 'form-control',
                'placeholder' => Module::t('default', 'CAPTION'),
            ]); ?>
            <br>
            <?= Html::textInput('url', $model->url, [
                'class'       => 'form-control',
                'placeholder' => Module::t('default', 'URL'),
            ]); ?>

            <div class="actions">
                <a href="<?= $model->src; ?>" rel="<?= $model->galleryId; ?>" class="fancy"
                   title="<?= $model->caption; ?>">
                    <i class="watch glyphicon glyphicon-eye-open" title="<?= Module::t('default', 'ORIGINAL'); ?>"></i>
                </a>

                <i class="edit glyphicon glyphicon-pencil" title="<?= Module::t('default', 'UPDATE_CAPTION'); ?>"></i>

                <i class="remove glyphicon glyphicon-trash" title="<?= Module::t('default', 'REMOVE_IMAGE'); ?>"></i>
            </div>
        </div>
    </div>
</div>
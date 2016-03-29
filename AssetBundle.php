<?php

namespace sadovojav\gallery;

/**
 * Class AssetBundle
 * @package sadovojav\gallery
 */
class AssetBundle extends \yii\web\AssetBundle
{
    public function init()
    {
        $this->sourcePath = __DIR__ . '/assets';
        $this->css = [
            'css/style.css',
            'js/fancybox/source/jquery.fancybox.css',
        ];
        $this->js = [
            'js/sortable/Sortable.min.js',
            'js/fancybox/source/jquery.fancybox.pack.js',
        ];
        $this->depends = [
            'yii\web\JqueryAsset',
        ];

        parent::init();
    }
}
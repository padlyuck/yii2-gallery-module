<?php

namespace sadovojav\gallery\widgets;

use sadovojav\gallery\models\Gallery as BaseGallery;
use Yii;
use yii\base\Widget;
use yii\caching\DbDependency;
use yii\helpers\Html;

/**
 * Class Gallery
 * @package sadovojav\gallery\widgets
 */
class Gallery extends Widget
{
    /**
     * @var
     */
    public $galleryId;
    /**
     * @var
     */
    public $galleryCode;
    /**
     * @var bool
     */
    public $caption = false;
    /**
     * @var
     */
    public $template;

    public function run()
    {
        $dependency = new DbDependency();
        $dependency->sql = 'SELECT MAX(updated) FROM {{%gallery}}';

        /** @var \sadovojav\gallery\Module $module */
        $module = Yii::$app->getModule('gallery');
        if ($this->galleryId) {
            $model = BaseGallery::getDb()->cache(function () {
                return BaseGallery::find()
                                  ->where('id = :id', [':id' => $this->galleryId])
                                  ->active()
                                  ->one();
            }, $module->queryCacheDuration, $dependency);
        } else {
            $model = BaseGallery::getDb()->cache(function () {
                return BaseGallery::find()
                                  ->where('code = :code', [':code' => $this->galleryCode])
                                  ->active()
                                  ->one();
            }, $module->queryCacheDuration, $dependency);
        }

        if (!$model || !$model->files && !$this->template) {
            return false;
        }

        if ($this->template) {
            return $this->render($this->template, [
                'model' => $model,
                'models' => $model->files,
            ]);
        } else {
            return $this->getDefaultGallery($model);
        }
    }

    /**
     * Get default gallery style image/caption
     *
     * @param $model
     *
     * @return string
     */
    private function getDefaultGallery($model)
    {
        $html = Html::beginTag('div', [
            'class' => 'content-gallery default gallery-' . $model->id,
        ]);

        foreach ($model->files as $value) {
            $html .= Html::beginTag('div');
            $html .= Html::img($value->src, [
                'alt' => $this->caption ? $value->caption : null,
                'class' => 'img-responsive',
            ]);

            if ($this->caption) {
                $html .= Html::tag('div', $value->caption, [
                    'class' => 'caption',
                ]);
            }

            $html .= Html::endTag('div');
        }

        $html .= Html::endTag('div');

        return $html;
    }
}
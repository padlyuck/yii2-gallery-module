<?php

namespace sadovojav\gallery;

use Yii;

/**
 * Class Module
 * @package sadovojav\gallery
 */
class Module extends \yii\base\Module
{
    /**
     * @var int
     */
    public $queryCacheDuration = 604800;

    /**
     * @var bool
     */
    public $uniqueName = false;

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    public function init()
    {
        parent::init();
        $this->registerTranslations();
        $this->controllerNamespace = 'sadovojav\gallery\controllers';
        $this->basePath = '@webroot/galleries';
    }

    public function registerTranslations()
    {
        Yii::$app->i18n->translations['sadovojav/gallery/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@vendor/sadovojav/yii2-gallery-module/messages',
            'fileMap' => [
                'sadovojav/gallery/default' => 'default.php',
            ],
        ];
    }

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('sadovojav/gallery/' . $category, $message, $params, $language);
    }
}

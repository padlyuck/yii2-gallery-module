<?php

namespace sadovojav\gallery\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\FileHelper;

/**
 * This is the model class for table "{{%gallery_file}}".
 *
 * @property integer $id
 * @property integer $galleryId
 * @property string  $file
 * @property string  $caption
 * @property string  $url
 * @property integer $position
 *
 * @property string  $path
 * @property string  $src
 */
class GalleryFile extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%gallery_file}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['galleryId', 'file'], 'required'],
            [['galleryId', 'position'], 'integer'],
            ['position', 'default', 'value' => 0],
            [['caption', 'file', 'url'], 'string', 'max' => 255],
        ];
    }

    /**
     * Get file src
     * @return string
     */
    public function getSrc()
    {
        $path = DIRECTORY_SEPARATOR . preg_replace('/^@(\w+)\//i', '', Yii::$app->getModule('gallery')->uploadPath);

        return FileHelper::normalizePath($path . DIRECTORY_SEPARATOR . $this->galleryId . DIRECTORY_SEPARATOR . $this->file,
            DIRECTORY_SEPARATOR);
    }

    /**
     * Get file path
     * @return bool|string
     */
    public function getPath()
    {
        return FileHelper::normalizePath(Yii::getAlias(Yii::$app->getModule('gallery')->uploadPath . DIRECTORY_SEPARATOR
            . $this->galleryId . DIRECTORY_SEPARATOR . $this->file));
    }
}

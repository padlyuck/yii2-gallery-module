<?php
use yii\db\Migration;
use yii\helpers\Console;

class m160526_090929_adding_url_to_slide extends Migration
{
    private $table;

    public function init()
    {
        $this->table = \sadovojav\gallery\models\GalleryFile::tableName();
        parent::init();
    }

    public function safeUp()
    {
        try {
            $this->addColumn($this->table, 'url', $this->string()->notNull()->defaultValue(''));
        } catch (\Exception $e) {
            echo Console::output(Console::ansiFormat($e->getMessage(), [Console::FG_RED]) . PHP_EOL);
            $this->down();
            return false;
        }
        return true;
    }

    public function safeDown()
    {
        try {
            $this->dropColumn($this->table, 'url');
        } catch (\Exception $e) {
            echo Console::output(Console::ansiFormat($e->getMessage(), [Console::FG_RED]) . PHP_EOL);
            return false;
        }
        return true;
    }
}

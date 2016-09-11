<?php
use yii\db\Migration;
use yii\helpers\Console;

class m160909_110007_gallery_code extends Migration
{
    private $table;

    public function init()
    {
        $this->table = \sadovojav\gallery\models\Gallery::tableName();
        parent::init();
    }

    public function safeUp()
    {
        try {
            $this->addColumn($this->table, 'code', $this->string()->notNull()->unique());
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
            $this->dropColumn($this->table, 'code');
        } catch (\Exception $e) {
            echo Console::output(Console::ansiFormat($e->getMessage(), [Console::FG_RED]) . PHP_EOL);

            return false;
        }

        return true;
    }
}

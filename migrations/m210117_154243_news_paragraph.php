<?php

use yii\db\Migration;

/**
 * Class m210117_154243_news_paragraph
 */
class m210117_154243_news_paragraph extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('news_paragraph', [
            'id' => $this->primaryKey(),
            'news_id'=> $this->integer()->notNull(),
            'text' => $this->text()->null(),
            'date_add'=> $this->integer()->notNull(),
            'date_update'=> $this->integer()->null()->defaultValue(null),
        ]);

        $this->createIndex('news_id', 'news_paragraph', 'news_id');

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropPrimaryKey("id", "news_paragraph");
        $this->dropIndex("news_id", "news_paragraph");
        $this->dropTable('news');

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210117_154243_news_paragraph cannot be reverted.\n";

        return false;
    }
    */
}

<?php

use yii\db\Migration;

/**
 * Class m210116_095647_news
 */
class m210116_095647_news extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('news', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'text' => $this->text()->notNull(),
            'link'=> $this->string(255)->notNull(),
            'source'=> $this->string(32)->notNull(),
            'guid'=> $this->string(32)->notNull(),
            'date_add'=> $this->integer()->notNull(),
            'date_update'=> $this->integer()->null()->defaultValue(null),
            'date_news'=> $this->dateTime()->notNull(),
        ]);

        $this->createIndex('source', 'news', 'source');
        $this->createIndex('guid', 'news', 'guid');
        $this->createIndex('date_news', 'news', 'date_news');

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropPrimaryKey("id", "news");
        $this->dropIndex("source", "news");
        $this->dropIndex("guid", "news");
        $this->dropIndex("date_news", "news");
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
        echo "m210116_095647_news cannot be reverted.\n";

        return false;
    }
    */
}

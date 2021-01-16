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
            'date_news'=> $this->string(19)->notNull(),
        ]);

        $this->createIndex('source', 'news', 'source');
        $this->createIndex('guid', 'news', 'guid');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropPrimaryKey("id", "news");
        $this->dropIndex("source", "news");
        $this->dropIndex("guid", "news");
        $this->dropTable('news');

        return false;
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

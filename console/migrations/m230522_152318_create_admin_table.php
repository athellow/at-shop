<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%admin}}`.
 */
class m230522_152318_create_admin_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%admin}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'email' => $this->string()->notNull()->unique(),
            'verification_token' => $this->string()->notNull()->defaultValue(''),
            'token' => $this->string()->notNull()->defaultValue(''),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'last_ip' => $this->string(32)->notNull()->defaultValue(''),
            'last_time' => $this->integer()->notNull()->defaultValue(0),
            'login_count' => $this->integer()->notNull()->defaultValue(0),
            'created_at' => $this->integer()->notNull()->defaultValue(0),
            'updated_at' => $this->integer()->notNull()->defaultValue(0),
        ], $tableOptions);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%admin}}');
    }
}

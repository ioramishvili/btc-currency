<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%users}}`.
 */
class m240210_111811_create_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up(): void
    {
        $this->createTable('users', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->unique()->notNull(),
            'access_token' => $this->string(64)->unique()->notNull(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('NOW()'),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression('NOW()'),
        ]);

        $this->insert('users', [
            'username' => 'example',
            'access_token' => 'pK5GkLAxQnY_3EujeayyOPt6ifgFRDa-UzyZOe.jFWTzA8wP1yyDDM3g0jVGwkQx'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function down(): void
    {
        $this->dropTable('users');
    }
}

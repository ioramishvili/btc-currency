<?php

declare(strict_types=1);

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "accounts_contracts".
 *
 * @property int $id
 * @property string $username
 * @property string $access_token
 * @property string $created_at
 * @property string $updated_at
 */
class User extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'users';
    }

    public function rules(): array
    {
        return [
            [['username', 'access_token'], 'unique'],
            [['username', 'access_token'], 'required'],
            [['username', 'access_token'], 'string'],
            [['created_at', 'updated_at',], 'safe'],
        ];
    }

    public function behaviors(): array
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'value' => new Expression('NOW()'),
            ],
            [
                'class' => TimestampBehavior::class,
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'username' => 'Имя пользователя',
            'access_token' => 'Ключ доступа',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления'
        ];
    }
}

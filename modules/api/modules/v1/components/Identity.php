<?php

declare(strict_types=1);

namespace app\modules\api\modules\v1\components;

use app\models\User;
use yii\web\IdentityInterface;

class Identity extends User implements IdentityInterface
{
    public static function findIdentity($id): ?IdentityInterface
    {
        return null;
    }

    public static function findIdentityByAccessToken($token, $type = null): Identity|IdentityInterface|null
    {
        return self::findOne(['access_token' => $token]);
    }

    public function getId()
    {
        return null;
    }

    public function getAuthKey()
    {
        return null;
    }

    public function validateAuthKey($authKey)
    {
        return null;
    }
}

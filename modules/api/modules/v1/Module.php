<?php

declare(strict_types=1);

namespace app\modules\api\modules\v1;

use app\modules\api\modules\v1\components\Identity;
use Yii;

class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\api\modules\v1\controllers';

    /**
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();

        Yii::$app->user->identityClass = Identity::class;
    }
}

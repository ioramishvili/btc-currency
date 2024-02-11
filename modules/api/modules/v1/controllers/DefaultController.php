<?php

declare(strict_types=1);

namespace app\modules\api\modules\v1\controllers;

use app\modules\api\modules\v1\components\CustomBearerAuth;
use app\modules\api\modules\v1\services\AbstractService;
use app\modules\api\modules\v1\services\ConvertService;
use app\modules\api\modules\v1\services\RatesService;
use Yii;
use yii\base\InvalidRouteException;
use yii\rest\Controller;
use yii\web\MethodNotAllowedHttpException;

class DefaultController extends Controller
{
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();

        $behaviors['bearerAuth'] = [
            'class' => CustomBearerAuth::class,
        ];

        return $behaviors;
    }

    /**
     * @throws InvalidRouteException
     */
    public function actionIndex(string $method, ?string $parameter = null): array
    {
        try {
            $service = Yii::$container->get($this->getService($method));

            $this->validateMethod($service);

            $data = $service->execute($parameter, Yii::$app->request->getBodyParams());
        } catch (\Throwable $e) {
            return [
                'status' => 'error',
                'code' => 403,
                'message' => 'Invalid token'
            ];
        }

        return [
            'status' => 'success',
            'code' => 200,
            'data' => $data
        ];
    }

    private function getService(string $method): string
    {
        return match ($method) {
            'rates' => RatesService::class,
            'convert' => ConvertService::class
        };
    }

    /**
     * @throws MethodNotAllowedHttpException
     */
    private function validateMethod(AbstractService $service): void
    {
        if (!$service->validate(Yii::$app->request->getMethod())) {
            throw new MethodNotAllowedHttpException();
        }
    }
}
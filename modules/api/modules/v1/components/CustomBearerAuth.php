<?php

declare(strict_types=1);

namespace app\modules\api\modules\v1\components;

use yii\filters\auth\HttpBearerAuth;

class CustomBearerAuth extends HttpBearerAuth
{
    /**
     * Handles case when authorization is not provided.
     * @param $response
     * @return mixed
     */
    public function handleFailure($response): mixed
    {
        $response->data = [
            'status' => 'error',
            'code' => 403,
            'message' => 'Invalid token',
        ];
        $response->setStatusCode(403, 'Invalid token');

        return $response;
    }
}
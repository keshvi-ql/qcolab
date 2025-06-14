<?php

declare(strict_types=1);

namespace App\Utility;

use Cake\Http\Response;

class AjaxResponseHelper
{
    /**
     * Generate a standardized AJAX JSON response.
     *
     * @param bool $success Whether the operation was successful.
     * @param string $message The response message.
     * @param array $data Additional data to include in the response.
     * @param int $statusCode HTTP status code.
     * @return \Cake\Http\Response
     */
    public static function createResponse($success, $message = '', $data = [], $statusCode = 200)
    {
        $response = new Response();
        $responseBody = [
            'success' => $success,
            'message' => $message,
            'data' => $data
        ];

        return $response->withType('application/json')
                        ->withStatus($statusCode)
                        ->withStringBody(json_encode($responseBody));
    }
}

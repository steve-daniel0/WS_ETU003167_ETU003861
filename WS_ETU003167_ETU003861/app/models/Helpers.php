<?php
namespace App\Models;

class Helpers
{
    /**
     * Envoie une réponse JSON standard
     */
    public static function jsonResponse($data = null, $status = 'success', $code = 200, $error = null, $meta = null)
    {
        http_response_code($code);
        $response = [
            'status' => $status,
            'data' => $data,
            'error' => $error,
        ];
        if ($meta !== null) {
            $response['meta'] = $meta;
        }
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }

    /**
     * Envoie une erreur JSON
     */
    public static function jsonError(string $code, string $message, int $httpCode = 500)
    {
        Helpers::jsonResponse(
            null,
            'error',
            $httpCode,
            [
                'code' => $code,
                'message' => $message
            ]
        );
    }
}

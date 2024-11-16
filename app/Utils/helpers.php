<?php

use Illuminate\Http\Response;


if (! function_exists('explodePermission')) {
    function explodePermission(array $permissions)
    {
        $menus = [];
        foreach ($permissions as $permission) {
            $action = explode('-', $permission['name']);
            $menus[] = [
                'resource' => $action[0],
                'actions' => [
                    $action[1],
                ]
            ];
        }
        return $menus;
    }
}

if (!function_exists('sendError')) {
    function sendError($message, $result = [], $code = Response::HTTP_NOT_FOUND)
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if (!empty($result)) {
            $response['data'] = $result;
        }

        return response()->json($response, $code);
    }
}
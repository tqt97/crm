<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class BaseController extends Controller
{
    const MSG_RETRIEVED = 'you have successfully retrieved the information';
    const MSG_CREATED = 'you have successfully created objects';
    const MSG_UPDATED = 'you have successfully modified objects';
    const MSG_DELETED = 'you have successfully deleted objects';
    const MSG_ERROR = 'an error occurred during processing';
    const MSG_NOT_FOUND = 'Not Found';

    /**
     * Success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($message, $result = [], $code = Response::HTTP_OK): JsonResponse 
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data' => $result,
        ];

        return response()->json($response, $code);
    }

    /**
     * Return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($message, $result = [], $code = Response::HTTP_NOT_FOUND): JsonResponse
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

    /**
     * Return forbidden response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendForbidden($message = 'Error forbidden', $result = [], $code = Response::HTTP_FORBIDDEN)
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

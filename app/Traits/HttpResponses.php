<?php

namespace App\Traits;

trait HttpResponses{

    /**
     * @param $data
     * @param $message
     * @param $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function success($data, $message = null, $code = 200): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => 'Request was successful.',
            'message' => $message,
            'data' => $data
        ], $code);
    }

    /**
     * @param $data
     * @param $message
     * @param $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function error($data, $message = null, $code): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => 'Error has occured...',
            'message' => $message,
            'data' => $data
        ], $code);
    }

}

<?php
/*
 * Author Dede Fajriansyah
 * Created on Fri Jul 12 2019 14:37:26
 * Email dede.fajriansyah97@gmail.com
 *
 * Copyright (c) 2019 FAJRIANSYAH.COM
 */

namespace Fajriansyah\Traits;

trait APIResponseBuilder
{
    private function headers()
    {
        return [
            "Access-Control-Allow-Origin" => "*",
            "Access-Control-Allow-Methods" => "GET, POST, PUT, PATCH, DELETE, OPTIONS",
            "Access-Control-Allow-Headers" => "Origin, Content-Type, Accept, X-Requested-With, X-Token-Auth, Authorization"
        ];
    }

    private function apiResponseBuilder(bool $success, string $message = "OK", $data = null, array $errors = [], string $error_code = null, $status_code = 500)
    {
        if ($success) {
            return response()->json([
                'success' => $success,
                'code' => $error_code,
                'message' => $message,
                'data' => $data
            ], $status_code);
        } else {
            return response()->json([
                'success' => $success,
                'code' => $error_code,
                'message' => $message,
                'errors' => $errors
            ], $status_code);
        }
    }

    public function apiFormRequestValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $responses = response()->json([
            'success' => false,
            'code' => '0412',
            'message' => 'Form Request Validation!',
            'errors' => $validator->errors()->toArray()
        ], 422);

        throw new \Illuminate\Http\Exceptions\HttpResponseException($responses);
    }

    /**
     * @param array $errors
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiSuccessResponse(string $message = "OK", $data = null)
    {
        return $this->apiResponseBuilder(true, $message, $data, [], '200', 200);
    }

    /**
     * @param array $errors
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiUnprocessableEntityResponse($errors = [])
    {
        return $this->apiResponseBuilder(false, "Unprocessable Entities!", [], $errors, "0391", 422);
    }

    /**
     * @param array $errors
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiInternalServerErrorResponse($errors = [])
    {
        return $this->apiResponseBuilder(false, "Internal Server Error!", [], $errors, "0462");
    }

    /**
     * @param array $errors
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiUnauthorizedResponse($errors = [])
    {
        return $this->apiResponseBuilder(false, "Unauthorized!", [], $errors, "0518", 401);
    }

    /**
     * @param array $errors
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiNotFoundResponse($errors = [])
    {
        return $this->apiResponseBuilder(false, "Not Found!", [], $errors, "0730", 404);
    }

    public function apiErrorResponse(\Exception $e)
    {
        if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
            return $this->apiNotFoundResponse([$e->getMessage()]);
        }

        return $this->apiInternalServerErrorResponse([$e->getMessage()]);
    }
}

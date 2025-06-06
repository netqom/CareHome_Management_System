<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Http\JsonResponse;

class ApiHandler extends ExceptionHandler
{
    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function render($request, Throwable $exception)
    {
        // Handle API exceptions
        if ($this->isApiRoute($request)) {
            return $this->handleApiException($exception);
        }

        return parent::render($request, $exception);
    }

    /**
     * Check if the request is for an API route.
     *
     * @param \Illuminate\Http\Request $request
     * @return bool
     */
    protected function isApiRoute($request)
    {
        //dd('sdfsdfsdf');
        return $request->is('api/*');
    }

    /**
     * Handle API exceptions.
     *
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\JsonResponse
     */
    protected function handleApiException(Throwable $exception)
    {
        // Default error message and status code
       // $message = 'Please check for the latest update of the app. If the issue persists, contact support at support@docryt.com.';
        $message = "Oops! It looks like we're having trouble connecting to the network.";
        $message .= "Please check your internet connection and try again.";
        $statusCode = JsonResponse::HTTP_INTERNAL_SERVER_ERROR;
        $message1= '';

        // Customize error messages and status codes based on exception types
        if ($this->isHttpException($exception)) {
            $statusCode = $exception->getStatusCode();
            $message = $exception->getMessage() ?: 'Http Exception';
        } elseif ($exception instanceof ModelNotFoundException) {
            $statusCode = JsonResponse::HTTP_NOT_FOUND;
            $message = 'Resource not found.';
        }else {
            // For other types of exceptions, we include the exception message for debugging purposes
            $message1 = $exception->getMessage();
        }
        return response()->json(['status' => 'error', 'message' => $message,'real_message' => $message1], 200);
    }
}

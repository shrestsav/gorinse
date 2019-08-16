<?php

namespace App\Exceptions;

use Exception;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        //Exceptions for API calls
        if($request->expectsJson()){
            if($exception instanceof NotFoundHttpException){
                return response()->json([
                    'status' => '404',
                    'errors' => 'This url does not exists'
                ], Response::HTTP_NOT_FOUND);
            }
            if($exception instanceof ModelNotFoundException){
                return response()->json([
                    'status' => '404',
                    'errors' => 'Data does not exists'
                ], Response::HTTP_NOT_FOUND);
            }
            if($exception instanceof ClientException){
                return response()->json([
                    'status' => '401',
                    'errors' => 'Code Error, Your code may have expired or doesnot match. Please try resending the code'
                ], 401);
            }
            if($exception instanceof HttpException){
                return response()->json([
                    'status' => '403',
                    'errors' => 'You do not have priviledge to access this route'
                ], 403);
            }
        }
        return parent::render($request, $exception);
    }
}

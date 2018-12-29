<?php

namespace App\Exceptions;

use App\Libs\Pay\PaymentException;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

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
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
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
        if ($exception instanceof ErrorMessageException){
            if ($request->expectsJson()){
                return response()->json(['code' => 'FAIL', 'msg' => $exception->getMessage()]);
            }else {
                return back()->withErrors($exception->getMessage())->withInput($request->all());
            }
        }

        if ($exception instanceof PaymentException){
            return response()->json(['code' => 'FAIL', 'msg' => $exception->getMessage()]);
        }

        return parent::render($request, $exception);
    }
}

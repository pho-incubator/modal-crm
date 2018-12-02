<?php

namespace Pho\Crm\Exception;

use Teapot\StatusCode;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;
use Zend\Diactoros\Response\HtmlResponse;

class ExceptionHandler
{
    public function handle(\Exception $ex)
    {
        switch (get_class($ex)) {

            default:
                $isDebug = config('app.debug');
                if ($isDebug) {
                    $whoops = new Run();
                    $whoops->pushHandler(new PrettyPageHandler());
                    $whoops->register();
                    $whoops->writeToOutput(false);
                    $whoops->allowQuit(false);
                    $output = $whoops->handleException($ex);
                }
                else {
                    $output = view('500.php');
                }
                $response = new HtmlResponse($output, StatusCode::INTERNAL_SERVER_ERROR);
        }

        return $response;
    }
}

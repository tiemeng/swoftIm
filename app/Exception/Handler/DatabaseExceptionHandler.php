<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: tiemeng
 * Date: 2019/9/11
 * Time: 14:31
 */

namespace App\Exception\Handler;


use Swoft\Error\Annotation\Mapping\ExceptionHandler;
use Swoft\Http\Message\Response;
use Swoft\Http\Server\Exception\Handler\AbstractHttpErrorHandler;
use Throwable;
use App\Exception\DatabaseException;

/**
 * Class DatabaseExceptionHandler
 * @package App\Exception\Handler
 * @ExceptionHandler(DatabaseException::class)
 */
class DatabaseExceptionHandler extends AbstractHttpErrorHandler
{

    /**
     * @param Throwable $e
     * @param Response $response
     *
     * @return Response
     */
    public function handle(Throwable $e, Response $response): Response
    {
        $response->withContentType('application/json','utf-8');
        return $response->withData([
            'code' => $e->getCode(),
            'msg' => $e->getMessage()
        ]);
    }
}
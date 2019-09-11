<?php

use App\Common\DbSelector;
use App\Process\MonitorProcess;
use Swoft\Crontab\Process\CrontabProcess;
use Swoft\Db\Pool;
use Swoft\Http\Server\HttpServer;
use Swoft\Task\Swoole\SyncTaskListener;
use Swoft\Task\Swoole\TaskListener;
use Swoft\Task\Swoole\FinishListener;
use Swoft\Rpc\Client\Client as ServiceClient;
use Swoft\Rpc\Client\Pool as ServicePool;
use Swoft\Rpc\Server\ServiceServer;
use Swoft\Http\Server\Swoole\RequestListener;
use Swoft\WebSocket\Server\WebSocketServer;
use Swoft\Server\SwooleEvent;
use Swoft\Db\Database;
use Swoft\Redis\RedisDb;
use App\Http\Middleware\LoginMiddleware;

return [
//    'logger'            => [
//        'flushRequest' => false,
//        'enable'       => false,
//        'json'         => false,
//    ],
    'httpDispatcher'    => [
        // Add global http middleware
        'middlewares'      => [
            // \Swoft\Whoops\WhoopsMiddleware::class,
            \Swoft\View\Middleware\ViewMiddleware::class,

        ],
        'afterMiddlewares' => [
            \Swoft\Http\Server\Middleware\ValidatorMiddleware::class
        ]
    ],
    'db'                => [
        'class'    => Database::class,
        'dsn'      => 'mysql:dbname=schat;host=127.0.0.1',
        'username' => 'root',
        'password' => '123456',
        'prefix' => 'chat_'
    ],
    'db.pool'          => [
        'class'    => Pool::class,
        'database' => bean('db')
    ],
    'migrationManager'  => [
        'migrationPath' => '@app/Migration',
    ],
    'redis'             => [
        'class'    => RedisDb::class,
        'host'     => '127.0.0.1',
        'port'     => 16379,
        'database' => 0,
        'option'   => [
            'prefix' => 'swoft:'
        ]
    ],
    'wsServer'          => [
        'class'   => WebSocketServer::class,
        'port'    => 18308,
        'on'      => [
            // Enable http handle
            SwooleEvent::REQUEST => bean(RequestListener::class),
            SwooleEvent::TASK   => bean(TaskListener::class),  // Enable task must task and finish event
            SwooleEvent::FINISH => bean(FinishListener::class)
        ],
        'debug'   => 1,
        // 'debug'   => env('SWOFT_DEBUG', 0),
        /* @see WebSocketServer::$setting */
        'setting' => [
            'task_worker_num'       => 12,
            'task_enable_coroutine' => true,
            'log_file' => alias('@runtime/swoole.log'),
        ],
    ],
    'lineFormatter'      => [
        'format'     => '%datetime% [%level_name%] [%event%]  %messages%',
        'dateFormat' => 'Y-m-d H:i:s',
    ],
    'noticeHandler'      => [
        'class'     => \Swoft\Log\Handler\FileHandler::class,
        'logFile'   => '@runtime/logs/notice.log',
        'formatter' => \bean('lineFormatter'),
//        'levels'    => 'notice,info,debug,trace',
        'levels'    => 'info,notice',
    ],
    'applicationHandler' => [
        'class'     => \Swoft\Log\Handler\FileHandler::class,
        'logFile'   => '@runtime/logs/error.log',
        'formatter' => \bean('lineFormatter'),
        'levels'    => 'error,warning',
    ],
    'logger'             => [
        'flushRequest' => false,
        'enable'       => true,
        'handlers'     => [
            'application' => \bean('applicationHandler'),
            'notice'      => \bean('noticeHandler'),
        ],
    ]
];

<?php
// 事件定义文件
return [
    'bind' => [
    ],

    'listen' => [
        'AppInit' => [

        ],
        'HttpRun' => [
            function(){
                ob_start();
                \app\common\HeaderLog::start();
                \think\facade\Db::listen(function ($sql, $time, $explain)  {
                    if (0 === stripos($sql, 'SHOW') or  0 === stripos($sql, 'CONNECT')) {
                        return false;
                    }
                    \app\common\HeaderLog::db($time, $sql, $explain);
                });
            }
        ],
        'HttpEnd' => [
            function () {
                \app\common\HeaderLog::show();
            },
        ],
        'LogLevel' => [],
        'LogWrite' => [],
    ],

    'subscribe' => [
    ],
];

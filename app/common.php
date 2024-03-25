<?php
// 应用公共文件
/**
 * header log
 *  more like   hlog($var,$var2,$var3...);
 * @param ...$argv
 * @return void
 */
function hlog(...$argv)
{

    // handle 1 $label
    $label = 'unnamed';
    // hlog('var_name',$var), hlog('var_name',$var,$var2,$var3...)
    if (count($argv) > 1 && is_string($argv[0]) && strlen($argv[0])) {
        $label = $argv[0];
        array_shift($argv);
    }
    // handle 2 $vars
    foreach ($argv as $item) {
        \app\common\HeaderLog::log($label, $item, 1);
    }
}

function thow_if($condition, $message)
{
    if ($condition) {
        throw new \Exception($message);
    }
}

// 整个系统统一json格式
function json_format($errno, $msg, $data)
{
    return compact('errno', 'msg', 'data');
}

function json_error($errno = 1)
{

    $msg = 'error_unknown';
    // 统一错误码管理，在这里
    $data = json_format($errno, $msg, []);
    return json($data, 400);
}

function json_success($rs)
{
    $data = json_format(0, 'ok', $rs);
    $data['getparams']=input('get.');
    $data['postparams']=input('post.');
    \app\common\HeaderLog::show();
    return json($data);
}
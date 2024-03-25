<?php
declare (strict_types=1);

namespace app\controller;

use think\Request;

class SystemAttribute
{
    function lists()
    {
        $data = [
            'time_period' => [
                ['value' => 30, 'label' => '30 days'],
                ['value' => 7, 'label' => '7 days']
            ],
            'sort' => [
                ['value' => 'create_time desc', 'label' => 'newest'],
                ['value' => 'likes desc', 'label' => 'likes desc']
            ],
        ];
        return json_success($data);
    }
}

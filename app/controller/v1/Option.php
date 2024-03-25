<?php
declare (strict_types=1);

namespace app\controller\v1;

use app\BaseController;
use think\Request;

class Option extends BaseController
{
    public function getPostOptions()
    {
        $data = [
            'sources' =>
                [
                    ['value' => 100000, 'text' => 'instagram'],
                    ['value' => 100001, 'text' => 'tiktok']
                ],
            'categories' =>
                [
                    ['value' => 200000, 'text' => 'clothing'],
                    ['value' => 200001, 'text' => 'bag']
                ],
            'time_period' => [
                ['value' => 30, 'text' => 'Less than 30 days'],
                ['value' => 7, 'text' => 'Less than 7 days']
            ],
            'sort' => [
                ['value' => 'create_time desc', 'text' => 'newest'],
                ['value' => 'likes desc', 'text' => 'likes desc']
            ],
        ];
        return json($data);
    }
}

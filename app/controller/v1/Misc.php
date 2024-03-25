<?php
declare (strict_types=1);

namespace app\controller\v1;

use app\model\CategoryModel;
use think\facade\Db;
use think\Request;

class Misc
{
    public function getMediumOptions()
    {
        $rs = Db::table('post_mediums')
            ->field('id value,medium_name label')
            ->select();
        return json_success($rs);
    }

    public function getCategoryOptions()
    {
        $rs = Db::table('post_categories')
            ->field('id value,category_name label')
            ->where('state',CategoryModel::STATE_OK)
            ->select();
        return json_success($rs);
    }

    function getAttrOptions()
    {
        $data = [
            'time_period' => [
                ['value' => 30, 'label' => '30 days'],
                ['value' => 7, 'label' => '7 days']
            ],
            'sort' => [
                ['value' => 'create_time desc', 'label' => 'newest'],
                ['value' => 'likes_count desc', 'label' => 'likes']
            ],
        ];
        // return json($data);
        return json_success($data);
    }
}

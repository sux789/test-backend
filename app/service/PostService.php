<?php
declare (strict_types=1);

namespace app\service;

use app\model\PostModel;
use think\App;

class PostService extends \think\Service
{
    protected $postModel = null;

    public function __construct(App $app, PostModel $postModel)
    {
        parent::__construct($app);
        $this->postModel = $postModel;
    }

    function search($keyword = '', $medium_id = 0, $category_id = 0, $time_period = '', $sort = '')
    {
        $list = $this->postModel
            ->when($medium_id, function ($query) use ($medium_id) {
                return $query->where('medium_id', $medium_id);
            })->when($category_id, function ($query) use ($category_id) {
                return $query->where('category_id', $category_id);
            })->when($time_period, function ($query) use ($time_period) {
                $time = date('Y-m-d H:i:s', time() - 60 * 60 * 24 * $time_period);
                return $query->whereTime('create_time', '>', $time);
            })->when($sort, function ($query) use ($sort) {
                return $query->order($sort);
            })->filter(function ($post) {
                $post['pub_date'] = substr($post['create_time'], 0, 10);
                return $post;
            })->select();
        return $list;
    }
}

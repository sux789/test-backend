<?php
declare (strict_types=1);

namespace app\controller\v1;

use app\BaseController;
use app\common\HeaderLog;
use think\Request;
use app\model\PostModel;
use app\common\ListEnricher;

class Post extends BaseController
{
    protected $postModel = null;

    public function __construct(PostModel $postModel)
    {
        $this->postModel = $postModel;
    }

    public function posts($keyword = '', $medium_id = 0, $category_id = 0, $time_period = '', $sort = '')
    {
        $list = $this->postModel
            ->when($medium_id, function ($query) use ($medium_id) {
                return $query->where('medium_id', $medium_id);
            })->when($category_id, function ($query) use ($category_id) {
                return $query->where('category_id', $category_id);
            })->select();

        if (!$list->isEmpty()) {
            $list = ListEnricher::enrichOne($list, 'user_id');
        }

        HeaderLog::log('posts', $list);
        return json_success($list);
    }

    function like($post_id)
    {

    }

    /**
     * 保存新建的资源
     *
     * @param \think\Request $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
    }

    /**
     * 显示指定的资源
     *4
     * @param int $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }


    /**
     * 保存更新的资源
     *
     * @param \think\Request $request
     * @param int $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 删除指定资源
     *
     * @param int $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }
}

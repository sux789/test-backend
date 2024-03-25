<?php
declare (strict_types=1);

namespace app\service;

use app\common\HeaderLog;
use think\App;
use think\facade\Db;
use app\model\PostModel;

class LikeService extends \think\Service
{
    protected $postModel = null;

    public function __construct(App $app, PostModel $postModel)
    {
        parent::__construct($app);
        $this->postModel = $postModel;
    }

    function liked($user_id, $post_id)
    {
        return (bool)$this->getPostUserMap($post_id, $user_id);
    }

    /**
     * 读取是liked map,支持post_id多个
     * @param $post_id
     * @param $user_id
     * @return array [post_id=>user_id] 哈希效率
     */
    function getPostUserMap($post_id, $user_id)
    {
        $rt = DB::table('post_likes')
            ->where('user_id', $user_id)
            ->whereIn('post_id', (array)$post_id)
            ->column('user_id', 'post_id');
        HeaderLog::log('getPostUserMap', $rt);
        return $rt;
    }

    /**
     * 点赞
     */
    function like($user_id, $post_id)
    {
        $rt = true;// 目标一致性
        if (!$this->liked($user_id, $post_id)) {
            $data = [
                'user_id' => $user_id,
                'post_id' => $post_id,
            ];
            // 如果这里放事务一些逻辑在事务外面，那在事务做检查，这就是悲观变乐观。
            Db::transaction(function () use ($data, &$rt) {
                $rt = (bool)DB::table('post_likes')->insert($data);
                // 代码顺序对性能极大影响，读公共资源放最后。
                // 比如上门服务技师要算距离，春节买车票。技师和车票是共的。
                $this->postModel->where('id', $data['post_id'])
                    ->inc('likes_count')
                    ->update();
                // 这用inc不用外部值也有意味
            });
        }
        return $rt;
    }

    function unlike($user_id, $post_id)
    {
        $rt = true;// 目标一致性
        if ($this->liked($user_id, $post_id)) {
            $map = [
                'user_id' => $user_id,
                'post_id' => $post_id,
            ];
            Db::transaction(function () use ($map, &$rt) {
                $rt = (bool)DB::table('post_likes')->where($map)->delete();
                $this->postModel->where('id', $map['post_id'])
                    ->where('likes_count', '>', 0)
                    ->dec('likes_count')
                    ->update();
            });
        }
        return $rt;
    }
}

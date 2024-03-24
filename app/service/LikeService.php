<?php
declare (strict_types=1);

namespace app\service;

use think\facade\Db;

class LikeService extends \think\Service
{
    function liked($user_id, $post_id)
    {
        return (bool)$this->getPostUserMap($post_id, $user_id);
    }

    function getPostUserMap($post_id, $user_id)
    {
        return DB::table('post_user')
            ->where('user_id', $user_id)
            ->whereIn('post_id', (array)$post_id)
            ->column('user_id', 'post_id');
    }

    function like($user_id, $post_id)
    {
        $rt = true;
        if (!$this->liked($user_id, $post_id)) {
            $data = [
                'user_id' => $user_id,
                'post_id' => $post_id,
            ];
            $rt = (bool)DB::table('post_likes')->insert($data);
        }
        return $rt;
    }
}

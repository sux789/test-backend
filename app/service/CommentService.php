<?php
declare (strict_types=1);

namespace app\service;
use think\facade\Db;
use think\App;

class CommentService extends \think\Service
{
    public function __construct(App $app)
    {
        parent::__construct($app);
    }

    // 这个地方不好
    function comment($user_id, $post_id, $comment)
    {
        $rs=DB::table('post_comments')
            ->insert([
                'user_id' => $user_id,
                'post_id' => $post_id,
                'comment' => $comment
            ]);
        if($rs){
            $comments_count=DB::table('post_comments')->where('post_id',$post_id)->count();
            DB::table('posts')->where('id',$post_id)->update(compact('comments_count'));
        }
        return $rs;
    }
}

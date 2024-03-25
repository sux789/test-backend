<?php
declare (strict_types=1);

namespace app\controller\v1;

use app\BaseController;
use app\common\HeaderLog;
use app\service\LikeService;
use think\Request;
use app\service\CommentService;
use app\service\PostService;
use app\common\ListEnricher;

class Post extends BaseController
{
    const ERRNO_COMMENT_LEN = 10001;
    protected $postService = null;
    protected $likeService = null;
    protected $commentService = null;

    public function __construct(PostService $postService, LikeService $likeService, CommentService $commentService)
    {
        $this->postService = $postService;
        $this->likeService = $likeService;
        $this->commentService = $commentService;
    }

    public function posts($keyword = '', $medium_id = 0, $category_id = 0, $time_period = '', $sort = '')
    {
        // step 1 search
        $list = $this->postService->search($keyword, $medium_id, $category_id, $time_period, $sort);

        // step 2 有限数据里面找到帖子对人我的点赞
        $post_ids = $list->column('id');
        $likedMap = $this->likeService->getPostUserMap($post_ids, $this->auth_id);

        // step 3 给字段设置点赞
        $list->filter(function ($post) use ($likedMap) {
            $post['liked'] = $likedMap[$post['id']] ?? false;
            return $post;
        });

        // set4 补充用户信息到结果中，这是一个特殊角度，简单直观
        $list = ListEnricher::enrichOne($list, 'user_id');

        return json_success($list);
    }

    function like($post_id)
    {
        $rs = $this->likeService->liked($this->auth_id, $post_id)
            ? $this->likeService->unlike($this->auth_id, $post_id)
            : $this->likeService->like($this->auth_id, $post_id);

        return json_success($rs);
    }

    function comment($post_id, $comment)
    {
        $comment = input('post.comment', '', 'trim');
        if (mb_strlen($comment) < 6) {  // 最佳 throw_if($bool, self::ERRNO_COMMENT_LEN)
            json_error(self::ERRNO_COMMENT_LEN, '评论内容不能少于6个字符');
        }

        $rs = $this->commentService->comment($this->auth_id, $post_id, $comment);
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

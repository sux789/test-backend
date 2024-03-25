create_time CURRENT_TIMESTAMP

state ok

apifox 

如果不使用模板，可以删除该目录

后端代码之常量，习惯
```php
class CategoryModel extends Model
{
    const STATE_OK = 1;
    const STATE_DISABLED = 2;
    static $stateDescription = [
        self::STATE_OK => '正常',
        self::STATE_DISABLED => '禁用'
    ];
    protected $name = 'post_categorys';
}
```
## 后端
###  分类只有一级，待优化
1. 只做一级分类
    1. 方法1 父子分类操作，实时读取树，while->listLevel()// 读取父子目录，需要记录已经读取，容易写不好读
    2. 方法2 通过字段记录路径，sql findInSet 或like比上面简单好一点，不利于索引优化
    3. 方法3 child_id,level,parent_id，直观,可以集合操作，尤其适合对传播练大数据量优化，索引长度4字节!下面这个图，前两个方法就是灾难了[](http://test.kono.top/static/images/pid-uid.png)
2. 连表
   3. 逻辑放在索引上面，结果用
4. 内部层次结构
```php
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
```
对应层次

|   |   |
|---|---|
|  postService->search | likeService->getPostUserMap  |
|   |   |

### mongodb es 操作区别
1, 任何数据库索引永远，没有es之前已用过倒排索引mama
2, 非关系不连表，有关系的放在一起了


1. 分类
1. 
2. todo:  分类父子关系
    1. 最佳实践

2. 安全验证方面，系统只有一个地方维护，这里不在重复制造轮子

2. 连表
1. 通常join,回表问题
2. 后端框架model复杂关系和复杂查询builder，都是一个目标，连表。
3. 这里 写了一个函数，会有很多变种，简单一点

3. 排序
1. 排序字段和where字段最好一直
2. 分页 offset ,limit 700000,20这样的不对，暂时没有分页，最好where id>700000 limit 20

4. 事务, 能想到的的因素
5. 统一管理
6. 统一输出
7. 统一错误码管理

### 后端

1. api 单独放在一个文件里面
2. 前端工程

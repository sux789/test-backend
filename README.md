## 实战测试 完成：4个下拉框搜索和评论点赞

前端github [链接](https://github.com/sux789/test-frontend)  
后端github [链接](https://github.com/sux789/test-backend)  
**部署地址** [链接](http://test.kono.top/)  可以看运行效果，也可以看调试sql如下运行图

## 能提高一个层次代码展示

1. 内部层次结构
   代码[链接](https://github.com/sux789/test-backend/blob/main/app/controller/v1/Post.php)

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

        // set4 补充用户信息到结果中，这是一个特殊角度，对应逻辑放在索引中特别有效
        $list = ListEnricher::enrichOne($list, 'user_id');

        return json_success($list);
    }
```

上面代码对应层次，上层共用下层，如堆积木

| 第一层 | v1/posts           |                            |
|:----|--------------------|----------------------------|
| 第二层 | postService/search | likeService/getPostUserMap |
| 第三层 | PostModel          | PostModel                  |

2. 代码内部管理
    1.
     ```php
     // 整个系统统一json格式 反之每个人去手动写，这个地方还有统一异常和错误码管理
     function json_format($errno, $msg, $data)
     {
         return compact('errno', 'msg', 'data');
     }
     ```
    1. 前端 api 管理
       api.js文件中，如果多了可以分类分层次放置 https://github.com/sux789/test_frontend/blob/master/src/api.js
    3. 另外 使用 http://test.kono.top/v1/misc/getAttrOptions 是统一管理一些下拉，比如男女，状态等等。
    4. api 版本管理 目前api可用都在 /v1/下面

3. 后端代码之常量，看起来不起眼
   app/model/CategoryModel.php

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

4. 事务,几乎所有人都很粗暴，以前遇到过问题解决过，有几层意思里面

```php
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
```

5. 在线调试 实战测试[部署在这个url](http://test.kono.top/),点击就可以调试出如下，开发维护效率
   ![](http://test.kono.top/static/images/debug.png)

6. 后端想法没有实现
    1. 只做已经分类，没有多级分类，
        1. 方法1 父子分类操作，实时读取树，while->listLevel()// 读取父子目录，需要记录已经读取，容易写不好读
        2. 方法2 通过字段记录路径，sql findInSet 或like比上面简单好一点，不利于索引优化
        3. 方法3 child_id,level,parent_id，直观,可以集合操作，尤其适合对传播练大数据量优化，索引长度4字节!
           下面这个图，前两个方法就是灾难了  
           ![](http://test.kono.top/static/images/pid-uid.png)  
           是逻辑放索引上的一个极小体现。

    4. 分页及其排序
        2. 分页 offset ,limit 700000,20这样的不对，暂时没有分页，最好where id>700000 limit 20
        3. 排序字段和where字段最好一直，只是经历过
    5. 计划v2/下面放es 搜索 。mysql文本搜索不合适。

### 表结构

1. mysql [表结构](https://github.com/sux789/test-backend/blob/main/schema.sql)
2. es 没有完成 https://github.com/sux789/test-backend/blob/main/app/service/ElasticSearchService.php

```php
        $properties = array(
            'id' => array('type' => 'integer',),
            'user_id' => array('type' => 'integer',),
            'medium_id' => array('type' => 'integer',),
            'category_id' => array('type' => 'integer',),
            'cover_image_src' => array('type' => 'text',),
            'comments_count' => array('type' => 'integer',),
            'likes_count' => array('type' => 'integer',),
            'create_time' => array('type' => 'date',),
            'update_time' => array('type' => 'date',),
            'content' => array('type' => 'text',),
            'comments' => array(
                'type' => 'nested',
                'properties' => array(
                    'id' => array('type' => 'integer',),
                    'user_id' => array('type' => 'integer',),
                    'content' => array('type' => 'text',),
                    'create_time' => array('type' => 'date',)
                ,)
            ,),
        );
        $mapping = ["properties" => $properties];
```

### 其他代码

2. [前端代码](https://github.com/sux789/test_frontend/blob/master/src/components/PostList.vue)

```vue

<script>
  /**
   * listPost 例子列表，是某命名规范。所有操作都刷新，是为了演示场景。操作后速度慢，请耐心等待。
   * commentPost 评论帖子，
   * likePost 点赞帖子
   * getSystemOptions 统一管理系统配置的选项属性
   */
  import {listPost, commentPost, likePost, getCategoryOptions, getMediumOptions, getSystemOptions} from '@/api';

  export default {
    data() {
      return {
        postList: [],
        mediumOptions: [],
        categoryOptions: [],
        timePeriodOptions: [],
        sortOptions: [],
        searchParams: {
          medium_id: "",
          category_id: "",
          keyword: "",
          time_period: "",
          sort: "",
        },
        commentInputRefs: [],// 评论输入框的refs
      };
    },
    watch: {
      // 监听5个搜索参数的变化，重新加载数据
      searchParams: {
        handler() {
          this.loadPostList();
        },
        deep: true
      }
    },
    methods: {
      showCommentInput(index) {
        this.postList[index].showCommentInput = true;
        this.$nextTick(() => {
          this.$refs['commentInput' + index][0].focus();
        });
      },
      toggleLike(index) {
        this.postList[index].liked = !this.postList[index].liked;
        likePost(this.postList[index].id).then(() => {
          //this.postList[index].likes += 1;
          this.loadPostList();
        })
      },
      submitComment(index) {
        this.postList[index].showCommentInput = false;
        commentPost(this.postList[index].id, this.postList[index].commentInput).then(() => {
          //this.postList[index].comments += 1;
          this.loadPostList();
        });
      },
      // reload posts list 在这个测试场景中，非常适合
      loadPostList() {
        listPost(this.searchParams).then((res) => {
          this.postList = res.data;
        });
      },
    },

    mounted() {
      this.loadPostList();
      getCategoryOptions().then((res) => {
        this.categoryOptions = res.data;
        console.log('categoryOptions', this.categoryOptions)
      });
      getMediumOptions().then((res) => {
        this.mediumOptions = res.data;
        console.log('mediumOptions', this.mediumOptions)

      });
      getSystemOptions().then((res) => {
        this.sortOptions = res.data.sort;
        this.timePeriodOptions = res.data.time_period;
        console.log('sortOptions', this.sortOptions)
        console.log('timePeriodOptions', this.timePeriodOptions)
      });
    },
  };
</script>
```

```php
  // mysql 搜索
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
```


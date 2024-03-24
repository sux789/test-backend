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

### mongodb es 操作区别
1, 任何数据库索引永远，没有es之前已用过倒排索引mama
2, 非关系不连表，有关系的放在一起了

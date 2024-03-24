<?php
declare (strict_types=1);

namespace app\model;

use think\Model;

/**
 * @mixin \think\Model
 */
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

<?php

namespace app\common;

use think\facade\Db;

/**
 * 以前项目中最直观的达到连表效果方式
 * 相比框架关系和query builder 直观，角度不一样.
 * ListEnricher::enrichOne($list, 'user_id'),会$list中加个字段user，值为user表的数据,只用一条sql select from user 语句
 * ListEnricher::enrichMulti($list, 'comment_id'),会$list中加个字段comments，值为该条数据所有评论
 */
class ListEnricher
{
    protected static $config = [
        'user_id' => ['foreign' => 'users.id', 'column_name' => 'user'],
    ];

    static function enrichOne($list, $field)
    {
        return self::enrich($list, $field);
    }

    static function enrichMulti($list, $field)
    {
        return self::enrich($list, $field, true);
    }


    // 多态，例如type不同关联不同表
    static function setMorph()
    {

    }

    private static function enrich($list, $field, $multi = false)
    {
        if (!is_array($list) && method_exists($list, 'toArray')) {
            $list = $list->toArray();
        }
        $ids = array_column($list, $field);
        list($table, $fk, $column_name) = self::parseConfig($field);
        thow_if(!$table, '表名称不能为空');
        if ($ids) {
            $enriched = Db::table($table)->whereIn($fk, $ids)->column('*', $fk);

            if ($multi) {
                foreach ($list as &$item) {
                    $index = $item[$field];
                    $item[$column_name][] = $enriched[$index];
                }
            } else {
                foreach ($list as &$item) {
                    $index = $item[$field];
                    $item[$column_name] = $enriched[$index];
                }
            }
            return $list;
        }
    }


    static function parseConfig($field)
    {
        $config = self::$config[$field] ?? [];
        //
        $table = '';
        $fk = '';
        if (strpos($config['foreign'], '.')) {
            list($table, $fk) = explode('.', $config['foreign']);
        } else {
            $table = $config['foreign'];
            $fk = 'id';
        }
        $column_name = $config['column_name'] ?? '';
        if (!$column_name) {
            if ('_id' == substr($field, -3)) {
                $column_name = substr($field, 0, -3);
            }
        }

        return [$table, $fk, $column_name];
    }

}
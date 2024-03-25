<?php
declare (strict_types=1);

namespace app\service;

use think\facade\Db;
use  Elastic\Elasticsearch\ClientBuilder;

class ElasticSearchService extends \think\Service
{
    const INDEX_NAME = 'posts';

    function sync()
    {
        $client = $this->getClient();
        $posts = Db::table(self::INDEX_NAME)->select();
        foreach ($posts as $post) {
            $params = [
                'index' => 'posts',
                'id' => $post['id'],
                'body' => [
                    'user_id' => $post['user_id'],
                    'medium_id' => $post['medium_id'],
                    'category_id' => $post['category_id'],
                    'cover_image_src' => $post['cover_image_src'],
                    'comments_count' => $post['comments_count'],
                    'likes_count' => $post['likes_count'],
                    'create_time' => $post['create_time'],
                    'update_time' => $post['update_time'],
                    'content' => $post['content'],
                ],
            ];
            $client->index($params);
        }
    }

    // 应该从配置中读取
    function getClient()
    {
        static $client = null;
        if (!$client) {
            $client = ClientBuilder::create()
                ->setHosts([
                    [
                        'host' => 'localhost',
                        'port' => 9200,
                        'scheme' => 'http',
                    ],
                ])
                ->build();
        }
        return $client;
    }

    function createIndex()
    {
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
        return $this->getClient()->createIndex(self::INDEX_NAME, $mapping);
        //eturn $this->getClient()->indices()->create($mapping);
    }
}

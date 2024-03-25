<?php
declare (strict_types=1);

namespace app\controller\v2;

use app\BaseController;
use app\service\ElasticSearchService;
use think\App;
use think\Request;

class Post extends BaseController
{
    protected $elasticSearchService;

    public function __construct(App $app, ElasticSearchService $elasticSearchService)
    {
        parent::__construct($app);
        $this->elasticSearchService = $elasticSearchService;
    }

    function posts()
    {
        $this->elasticSearchService->createIndex();
    }
}

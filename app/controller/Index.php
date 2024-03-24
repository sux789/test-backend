<?php

namespace app\controller;

use app\BaseController;
use app\service\UserService;
use think\App;
use think\View;

class Index extends BaseController
{
    protected $userService;
    public function __construct(App $app,UserService $userService)
    {
        parent::__construct($app);
        # 注入服务sss 126
        $this->userService=$userService;// service
    }

    public function index()
    {
        return \view('index');
    }

    public function hello($name = 'ThinkPHP8')
    {
        return 'hello,' . $name;
    }
}

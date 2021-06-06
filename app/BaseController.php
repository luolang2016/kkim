<?php
declare (strict_types=1);

namespace app;

use think\App;
use think\exception\ValidateException;
use think\facade\Config;
use think\Validate;

/**
 * 控制器基础类
 */
abstract class BaseController
{
    /**
     * Request实例
     * @var \think\Request
     */
    protected $request;

    /**
     * 应用实例
     * @var \think\App
     */
    protected $app;

    /**
     * 是否批量验证
     * @var bool
     */
    protected $batchValidate = false;

    /**
     * 控制器中间件
     * @var array
     */
    protected $middleware = [];

    //*****定义**************************************************************************************
    /**
     * 系统参数配置
     */
    public $_setting;

    /**
     * 全部的GET和POST参数
     */
    public $_all_params;

    /**
     * 全部的GET参数
     */
    public $_all_get;

    /**
     * 全部的POST参数
     */
    public $_all_post;

    /**
     * 当前时间戮
     */
    public $_cur_timestamp;
    //*****定义**************************************************************************************

    /**
     * 构造方法
     * @access public
     * @param App $app 应用对象
     */
    public function __construct(App $app)
    {
        $this->app = $app;
        $this->request = $this->app->request;

        // 控制器初始化
        $this->initialize();
    }

    // 初始化
    protected function initialize()
    {
        $this->_setting = Config::get('setting');
        $this->_all_params = $this->request->param();
        $this->_all_get = $this->request->get();
        $this->_all_post = $this->request->post();
        $this->_cur_timestamp = time();
    }

    /**
     * 验证数据
     * @access protected
     * @param array $data 数据
     * @param string|array $validate 验证器名或者验证规则数组
     * @param array $message 提示信息
     * @param bool $batch 是否批量验证
     * @return array|string|true
     * @throws ValidateException
     */
    protected function validate(array $data, $validate, array $message = [], bool $batch = false)
    {
        if (is_array($validate)) {
            $v = new Validate();
            $v->rule($validate);
        } else {
            if (strpos($validate, '.')) {
                // 支持场景
                [$validate, $scene] = explode('.', $validate);
            }
            $class = false !== strpos($validate, '\\') ? $validate : $this->app->parseClass('validate', $validate);
            $v = new $class();
            if (!empty($scene)) {
                $v->scene($scene);
            }
        }

        $v->message($message);

        // 是否批量验证
        if ($batch || $this->batchValidate) {
            $v->batch(true);
        }

        return $v->failException(true)->check($data);
    }

    /**
     * 处理并获取分页参数
     * @param array $params 参数
     * @param int $len 默认分页的页大小
     * @return array
     */
    public function pagerParams($params = [], $len = 20)
    {
        if (empty($params)) {
            $params = $this->_all_get;
        }
        $page = isset($params['page']) && (int)$params['page'] > 0 ? (int)$params['page'] : 1;
        $limit = isset($params['limit']) && (int)$params['limit'] > 0 ? (int)$params['limit'] : $len;
        if ($limit > 30) { //限制最多只能查30条数据
            $limit = 30;
        }
        $offset = ((int)$page - 1) * (int)$limit;
        return [
            'page' => $page,
            'limit' => $limit,
            'offset' => $offset
        ];
    }

    /**
     * 成功时反回
     * @param string $msg 消息
     * @param array $data 数据
     */
    public function success($msg = 'success', $data = array())
    {
        $data = [
            'code' => 1,
            'msg' => $msg,
            'data' => $data
        ];
        echo json_encode($data);
        exit();
    }

    /**
     * 失败时返回
     * @param string $msg 消息
     * @param array $data 数据
     */
    public function error($msg = 'fail', $data = array())
    {
        $data = [
            'code' => 0,
            'msg' => $msg,
            'data' => $data
        ];
        echo json_encode($data);
        exit();
    }

}

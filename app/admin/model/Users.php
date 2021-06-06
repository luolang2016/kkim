<?php
/**
 * 项目 KKIM.
 * Copyright (c) 2017-2020.
 * Date: 2020/12
 * Author: YiMing
 * Mail: 641612700@qq.com
 */

namespace app\admin\model;

use app\common\utils\Paging;
use think\Model;

class Users extends Model
{

    public function getList($adm, $paramsAll, $pager, $pageLink = '/admin/users/list?page={P}')
    {
        $where = null;

        //where...

        $count = self::$db->name('users')->where($where)->count('id');

        if ($count <= 0) {
            $result = [
                'list' => [], 'count' => $count, 'curPage' => $pager['page'],
                'pageSize' => $pager['limit'], 'pageCount' => 0
            ];
        } else {
            $field = 'id,account,avatar,nickname,signature,realname,gender,reg_time,last_login_time,status';
            $list = self::$db->name('users')
                ->field($field)
                ->where($where)
                ->order('id DESC')
                ->limit($pager['offset'], $pager['limit'])
                ->select();
            $pageCount = ceil($count / $pager['limit']); //总页数
            $result = [
                'list' => $list, 'count' => $count, 'curPage' => $pager['page'],
                'pageSize' => $pager['limit'], 'pageCount' => $pageCount
            ];
        }

        $paging = new Paging($pageLink, $count, $pager['limit'], $pager['page']);
        $pageLink = $paging->pg_write();

        $result['pageLink'] = $pageLink;

        return $result;
    }

}

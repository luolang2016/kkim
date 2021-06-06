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

class ChatRecord extends Model
{

    public function getList($adm, $paramsAll, $pager, $pageLink = '/admin/record/list?page={P}')
    {
        $where = null;

        //where...

        $count = self::$db->name('chat_record')->where($where)->count('id');

        if ($count <= 0) {
            $result = [
                'list' => [], 'count' => $count, 'curPage' => $pager['page'],
                'pageSize' => $pager['limit'], 'pageCount' => 0
            ];
        } else {
            //TODO::注，在此列表读取content字段并不科学，请实际使用时另外设计
            $field = 'id,sender_id,receive_id,content,create_time';
            $list = self::$db->name('chat_record')
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

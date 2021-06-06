<?php
/**
 * 项目 KKIM.
 * Copyright (c) 2017-2020.
 * Date: 2020/12
 * Author: YiMing
 * Mail: 641612700@qq.com
 */

namespace app\common\model;

use app\common\utils\Encrypt;
use app\common\utils\Tools;
use think\facade\Db;
use think\Model;

class Users extends Model
{

    //创建新用户
    public function createNewUser($params, $timestamp)
    {
        //判断账号是否已存在
        $data = Db::name('users')->where("account='" . $params['account'] . "'")->find();
        if (!empty($data)) {
            return ['res' => false, 'msg' => '已经存在的账号', 'data' => []];
        }
        //设置数据
        $usData = $this->setRegData($params, $timestamp);
        //写入
        try {
            //::注意：如果这里的$usData是空数组[]，则不会抛出异常的
            $usData['id'] = Db::name('users')->strict(false)->insertGetId($usData);
            return ['res' => true, 'msg' => 'success', 'data' => $usData];
        } catch (\Throwable $e) {
            return ['res' => false, 'msg' => $e->getMessage(), 'data' => []];
        }
    }

    //仅用于登录时得到和更新用户
    public function getUser($params, $timestamp)
    {
        $us = self::where('account', $params['account'])->find();
        if ($us) {
            $md5Pwd = Encrypt::optimizedSaltPwd($params['password'], $us->salt);
            if ($md5Pwd != $us->password) {
                return false;
            }
            //$us->strict(false)->update($data);
            $us->last_login_time = $timestamp;
            $us->last_login_ip = Tools::getClientIp();
            $us->login_count = $us->login_count + 1;
            $us->save();
            return $us->toArray();
        }
        return false;
    }

    //注册时设置用户数据
    public function setRegData($params, $timestamp = null)
    {
        $salt = Tools::getRandChar(6);
        $password = Encrypt::optimizedSaltPwd($params['password'], $salt);
        if ($timestamp === null) {
            $timestamp = time();
        }
        return [
            'leader_id' => 0,
            'account' => $params['account'],
            'password' => $password,
            'salt' => $salt,
            'avatar' => '/images/head/' . $this->rndAvatar() . '.jpg',
            'nickname' => $this->rndNickName(),
            'signature' => '',
            'realname' => '',
            'gender' => '2',
            'money' => '0.00',
            'reg_time' => $timestamp,
            'last_login_time' => $timestamp,
            'last_login_ip' => Tools::getClientIp(),
            'login_count' => 1,
            'status' => 'normal'
        ];
    }

    private function rndNickName()
    {
        $nick = [
            '令代天', '雍菊华', '军依萱', '巨方方', '悉泰和', '市昆纬', '大碧玉', '原山芙', '练初瑶', '孔博实', '字浩', '势水卉', '薄绮玉', '姜龙', '嘉高朗', '鲍香卉', '迮瑾', '同昱', '粟幼安', '摩娟秀', '渠念', '戊圣杰', '雀慧丽', '佼长卿', '颜兰娜', '揭菱凡', '陶瑜', '盘顺', '旗白凡', '钟离光济', '华达', '瓮承业', '阚良才', '崇艳娇', '逄楠', '乌孙依玉', '侨成和', '溥静云', '贸涵忍', '之以柳', '节惠君', '钞清霁', '芒妮', '侍双文', '闽晗', '黎忆枫', '功博瀚', '胥之玉', '守涵涤', '皇翠琴', '利痴柏', '资香之', '锺离天骄', '枚智', '朴白秋', '嬴愉婉', '圣绮波', '招白枫', '祖名', '丘千凝', '昌旎', '母志新', '问秀妮', '文韶美', '谏俊哲', '和阳华', '阴梓颖', '闭瑞', '南雨莲', '恽杏', '巧瀚彭', '己雨', '苌初蝶', '碧桐欣', '寸衍', '钮代', '隆凌青', '庄子骞', '所宛', '忻醉冬', '波灵萱', '墨玉泉', '寿娟丽', '喜烨伟', '书高原', '折含香', '么怜容', '谷梁纯', '兰远悦', '类迎梅', '夏瓃', '梁丘又青', '慕瑾瑶', '姓如云', '赫燕桦', '崔思溪', '戈宜修', '璩含景', '翦茂学', '支平萱', '勤沛凝', '鱼夜', '干天巧', '徐天心', '衣鸿畴', '余真茹', '戚幼仪', '夹谷锦', '保昕珏', '厍天华', '郑自', '赏以珊', '斋迎蓉', '廖凯', '但凡灵', '肥丽珠', '受娅玟', '堂思凡', '赛戈雅', '怀明知', '京英逸', '栋恨瑶', '频弘图', '抄从蓉', '单于抒怀', '温勇锐', '鄂宜', '务珹', '素春竹', '碧鲁雅美', '遇采', '达英飙', '甘寒凝', '清俊拔', '舒芹', '可玮琪', '游山菡', '铎童欣', '淡雯', '慈彭祖', '琦康伯', '羊建明', '说曼冬', '虎沛文', '蒉问梅', '湛曜栋', '居梓楠', '张梦山', '让俊捷', '斛沛儿', '百采枫', '奈流如', '诺妙春', '缑运骏', '庹书易', '亓泰', '系飞沉', '倪令璟', '关童', '汝春柔', '完飞鹏', '才珍', '斐芳荃', '危从蓉', '凤爱', '性飞英', '芮晴波', '腾丽佳', '裘书雁', '蒋良哲', '法阳飙', '塔浩初', '僧志学', '汉兰英', '宗长娟', '牟韶仪', '庞经', '浮溥', '步白翠', '休仙', '东门清逸', '曹绮烟', '仲孙思楠', '伯衍', '赫连瑛', '后妤', '壤驷秋露', '弭珂', '甫醉山', '汲荌荌', '郦起运', '诗涵山', '林凡儿', '板海白', '卜游', '谯昊空', '宾痴梅', '咸布', '皋穹', '尉迟樱', '班芷琪', '阎茹云', '励高雅', '狄星睿', '潜静淑', '尔鸿文', '晁以丹', '臧天真', '脱灵松', '田国源', '段小春', '安沛山', '修雁风', '农星纬', '海颖慧', '税凌春', '万俟丹南', '撒智宸', '勇高达', '常凝雨', '买笑柳', '公冶桀', '公西夜绿', '焦昌勋', '鲁和暄', '寻仙韵', '牢德水', '储琼怡', '野高扬', '宝俊雄', '杭鸣', '巩智美', '局翔宇', '旅虹英', '莱水', '应梓菱', '过振平', '须嘉德', '长孙忆彤', '鄞勋', '禽玉环', '眭兰月', '尹诗双', '夔青柏', '謇娜兰', '掌朔', '浦聪', '郜春晓', '检岑', '孙梦竹', '潮星然', '谭乐池', '韦秉', '沈蕊珠', '司马舒畅', '雪初晴', '桓怀莲', '乐正小蕾', '希飞兰', '羽夏彤', '符清心', '濯时', '富察凡阳', '犹灿灿', '依奇略', '苟欣笑', '奚问兰', '涂宏壮', '丹西', '端乐蕊', '愈靖巧', '穆思聪', '义悠', '紫凌丝', '黄冰洁', '果舒方', '阿来', '蹇婀娜', '邝映波', '仪修雅', '贲鸿轩', '坚妙晴', '宗政凌兰', '止暄文', '凭问丝', '长韶华', '淳于水蓉', '乙雄', '植歌', '竭苑博', '代夏瑶', '桂晶灵', '环允', '合秀华', '咎向', '桥瑰玮', '友语晨', '乜芃', '洪清晖', '春子帆', '营飞宇', '仲高爽', '简雨泽', '祝雁卉', '沃忆文', '丛映雁', '席祯', '菅惠心', '兆思思', '朱慕晴', '糜清华', '弓春华', '蓟涵润', '屈蓉', '翟寻芳', '罗皎月', '留语冰', '巫马修洁', '齐晗玥', '礼映冬', '召问芙', '那拉昆宇', '楼楚洁', '玄维运', '谢锐智', '肇爰', '生黎', '仰阳夏', '权傲丝', '郝宏大', '刘好洁', '豆甘', '是秀竹', '汤映寒', '年瑰', '费思美', '霜半烟', '佴暄玲', '辜从蓉', '闫温茂', '赵元龙', '贯靖易', '鄢阳舒', '祈顺慈', '蔚蕴秀', '隗安福', '盍如心', '柔俊郎', '勾阳冰', '管菲', '接洁'
        ];
        $count = count($nick);
        $rnd = rand(0, $count - 1);
        return $nick[$rnd];
    }

    private function rndAvatar()
    {
        $head = [
            '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30'
        ];
        $count = count($head);
        $rnd = rand(0, $count - 1);
        return $head[$rnd];
    }

}

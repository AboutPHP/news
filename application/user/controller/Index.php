<?php
/**
 * Created by PhpStorm.
 * User: sunzhaoquan
 * Date: 2017/7/18
 * TIME:16:19
 */
namespace app\user\controller;
use app\admin\model\ArticleModel;
use app\common\Base;
use think\Db;
use think\Request;
class Index extends Base
{

    /**
     * [index  用户中心首页]
     * @by author sunzhaoquan
     */
    public function index(){
        $check_info = $this->check_news();
        $pass_info = $this->pass_news();
        $unpass_info = $this->unpass_news();
        $del_info = $this->del_news();
        $errdata = $this->recArticle();
        $this->assign([
            'status_1'=>$check_info,
            'status_2'=>$pass_info,
            'status_3'=>$unpass_info,
            'status_0'=>$del_info,
            'errdata'=>$errdata,
        ]);
        return view('index');
    }
    /**
     * [recArticle  最新文章纠错信息]
     * @by author  chengaoyuan
     */
    public function recArticle()
    {
        //最新纠错文章信息
        $errdata = db('article_error')->alias('e')->join('news_article a', 'a.id=e.article_id')
            ->field('e.*,a.title')
            ->order('update_time desc')
            ->limit('2')
            ->select();
        return $errdata;
    }
    /*
     * [check_news  获取最新的待审核新闻信息]
     * @by author sunzhaoquan
     */
    public function check_news(){
        $data = Db::name('article')
            ->where(['status'=>'1'])
            ->field('title,create_time,status')
            ->order('create_time desc')
            ->limit(1)
            ->find();
        return $data;
    }
    /**
     * [pass_news  获取最新的已通过新闻信息]
     * @by author sunzhaoquan
     */
    public function pass_news(){
        $data = Db::name('article')
            ->where(['status'=>'2'])
            ->field('title,update_time,status')
            ->order('update_time desc')
            ->limit(1)
            ->find();
        return $data;
    }
    /**
     * [unpass_news  获取最新的未通过新闻信息]
     * @by author sunzhaoquan
     */
    public function unpass_news(){
        $data = Db::name('article')
            ->where(['status'=>'3'])
            ->field('title,update_time,status')
            ->order('update_time desc')
            ->limit(1)
            ->find();
        return $data;
    }
    /**
     * [del_news  获取最新的已删除新闻信息]
     * @by author sunzhaoquan
     */
    public function del_news(){
        $data = Db::name('article')
            ->where(['status'=>'0'])
            ->field('title,update_time,status')
            ->order('update_time desc')
            ->limit(1)
            ->find();
        return $data;
    }
}
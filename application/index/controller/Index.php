<?php
namespace app\index\controller;
//use app\index\model\ArticleModel;
use app\admin\model\ArticleCateModel;
use app\admin\model\ArticleModel;
use app\common\Base;
use think\Db;
use think\Request;
use think\Response;

class Index extends Base
{
    /*
     * [index 新闻首页]
     * @author sunzhaoquan
     * */
    public function index()
    {

        $ArticleCate = new ArticleCateModel();
        $cate_info = $ArticleCate->getCateByStatus();
        $commend = $this->commend();
        $right_info = $this->views();
        $this->assign('right_info',$right_info);
        $this->assign('commend',$commend);
        $this->assign('cate_info',$cate_info);

        return $this->fetch();
	}
    /*
     * [art_show 新闻信息展示]
     * @param cate_id 分类id
     * @author sunzhaoquan
     * */
    public function art_show(){

        $cate_id = input('get.cate_id');
        $config['page'] = input('get.page',1);
        $limit = input('get.limit',10);
        $ArticleCate = new ArticleCateModel();
        $arts_info =$ArticleCate
            ->join('news_article','news_article.cate_id = news_article_cate.id')
            ->where([
                'news_article_cate.path'=>['like','-'.$cate_id.'%'],
                'news_article.status'=>'2',
            ])
            ->paginate($limit,false,$config)
            ->toArray();
        $arts_info['pages']= intval(ceil($arts_info['total']/$limit));

        return json($arts_info);
    }
    /**
     * [commend 新闻推荐-信息]
     * @author sunzhaoquan
     * */
    public function commend(){
        $im = new ArticleModel();
        $com_info = $im->commend_info();
        return $com_info;
    }
    /**
     *[views 点击量最多的10调新闻]
     */
    public function views(){
        $im = new ArticleModel();
        $top_info = $im->top_article();
        return $top_info;
    }
}

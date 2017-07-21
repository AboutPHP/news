<?php
/**
 * Created by PhpStorm.
 * User: sunzhaoquan
 * Date: 2017/7/5 0005
 * TIME:16:19
 */
namespace app\user\controller;
use app\admin\model\ArticleModel;
use app\common\Base;
use think\Request;
class News extends Base
{

    /**
     * [showlist  我的发布]
     * @by author wangchanghao
     */
    public function showlist(){

        $status = input('status',2); //文章状态

        $map['news_article.status'] = $status;

        $config['page'] = input('get.page') ? input('get.page'):1;
        $this->assign('status', $status);
        if(input('get.page'))
        {
            $Article = new ArticleModel();
            $result = $Article->getArticleByWhere($map,10,$config);
            return json($result);
        }
        return view('showlist');
    }

    /**
     * [articleDel() 删除文章 ;  articleRec() 恢复文章]
     * @by author chengaoyuan
     */
    public function articleDel()
    {

        $param=input('param.');

        $Article = new ArticleModel();
        $article = $Article->getOneArticle($param['id']);
        try{
            if($article['status'] == 0)
            {
                $Article->where(array('id'=>$param['id']))->setField(['status'=>1]);
            } else {
                $Article->where(array('id'=>$param['id']))->setField(['status'=>0]);
            }
            return json(['code' => 1, 'data' => '', 'msg' => $param['status'].'成功']);
        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $param['status'].'失败'];
        }

    }

    /**
     * [release 添加新闻]
     * @by author sunzhaoquan
     * */
    public function release()
    {
        if(request()->isPost()){
            $cout = new \app\admin\model\ArticleModel();
            if(request()->isAjax()){
                $param = input('post.');
                $param['create_time'] = time();
                $param['update_time'] = time();
                $flag = $cout->insertArticle($param);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }
        }else{
            $cate = new \app\admin\model\ArticleCateModel();
            $list = $cate->getAllCate();
            $list =$this->cate_tree($list);
            $this->assign('list',$list);
            return $this->fetch('release');
        }
    }
    /**
     * [modify 修改新闻]
     * @by author sunzhaoquan
     * */
    public function modify(Request $request){
        $cout = new \app\admin\model\ArticleModel();
        $cate = new \app\admin\model\ArticleCateModel();
        if (request()->isAjax()){
            $param = input('post.');
            $param['status']='1';
            $flag = $cout->editArticle($param);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
        $id = $request->instance()->param('id');
        $list = $cate->getAllCate();
        $list =$this->cate_tree($list);
        $article_info = $cout->getOneArticle($id);
        $this->assign('article',$article_info);
        $this->assign('cate',$list);
        return view('modify');
    }
    /**
     * [cate_tree 获取无限极分类列表]
     * @by author sunzhaoquan
     * */
    public function cate_tree($cate_info){
        /*无限极分类*/
        $nav = new \org\Leftnav;
        $list = $nav::rule($cate_info);
        return $list;
    }
}
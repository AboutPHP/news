<?php

namespace app\admin\controller;
use app\admin\model\ArticleModel;
use app\admin\model\CommentModel;
use think\Db;

class Article extends Base
{


    /**
     * [index 文章列表]
     * @return [type] [description]
     *
     */
    public function index(){

        $key = input('key');
        $map = [];
        if($key&&$key!==""){
            $map['title'] = ['like',"%" . $key . "%"];
        }
        $config['page'] = input('get.page') ? input('get.page'):1;
        $this->assign('val', $key);
        if(input('get.page'))
        {
            $Article = new ArticleModel();
            $result = $Article->getArticleByWhere($map,10,$config);
            return json($result);
        }
        return $this->fetch();
    }


    /**
     * [userAdd 添加文章]
     * @return [type] [description]
     *
     */
    public function add_ad()
    {
        $cout = new \app\admin\model\ArticleModel();
        $cate = new \app\admin\model\ArticleCateModel();
        if(request()->isAjax()){

            $param = input('post.');
            $flag = $cout->insertArticle($param);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
        $cates = $cate->getAllCate();
        $cates =$this->cate_tree($cates);
        $this -> assign('cates',$cates);
        return $this->fetch('article/add_article');
    }

    /**
     * [edit_ad 修改文章]
     * @return [type] [description]
     *
     */
    public function edit_ad(){
        $cout = new \app\admin\model\ArticleModel();
        $cate = new \app\admin\model\ArticleCateModel();
        if (request()->isAjax()){
            $param = input('post.');
            $flag = $cout->editArticleField(['status'=>$param['status'],'back'=>$param['back']],['id' => $param['id']]);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
        $id = input('param.id');
        $list = $cate->getAllCate();
        $list =$this->cate_tree($list);
        $this->assign('article',$cout->getOneArticle($id));
        $this->assign('cate',$list);

        return $this->fetch('article/edit_article');
    }

    /**
     * [del_ad 删除文章]
     * @return [type] [description]
     * @author [江湖] [1013137811@qq.com]
     */
    public function del_ad ()
    {
        $id = input('id');
        $Comment = new CommentModel();
        $comments = $Comment->field('id')->where(array('article_id'=>$id))->select();
        if ($comments){
            try{
                foreach ($comments as $comment){
                    CommentModel::destroy($comment['id']);
                }
            }catch (Exception $e){
                return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
            }
        }
        try{
            ArticleModel::destroy($id);
        }catch (Exception $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
        return json(['code' => 1, 'data' => '', 'msg' => '删除成功']);
    }

    /**
     * [is_tui 文章推荐为]
     * @return [type] [description]
     *
     */
    public function is_tui()
    {
        $id = input('param.id');
        $status = Db::name('article')->where(array('id'=>$id))->value('is_tui');//判断当前状态情况
        if($status==1)
        {
            $flag = Db::name('article')->where(array('id'=>$id))->setField(['is_tui'=>0]);
            return json(['code' =>0, 'data' => $flag['data'], 'msg' => '不推荐']);
        }else {
            $flag = Db::name('article')->where(array('id'=>$id))->setField(['is_tui'=>1]);
            return json(['code' => 1, 'data' => $flag['data'], 'msg' => '推荐']);
        }
    }




    /**
     * [index_cate 分类列表]
     * @return [type] [description]
     *
     */
    public function index_cate(){
        $cate = new \app\admin\model\ArticleCateModel();
        $list = $cate->getAllCate();
        $list =$this->cate_tree($list);
        $this->assign('list',$list);
        return $this->fetch();
    }


    /**
     * [add_cate 添加分类]
     * @return [type] [description]
     *
     */
    public function add_cate()
    {
        if(request()->isAjax()){

            $param = input('post.');
            $cate = new \app\admin\model\ArticleCateModel();
            $p_info = $cate->where(['id'=>$param['pid']])->find();
            $flag = $cate->insertCate($param);
            if($flag){
                $id = $cate->getLastInsID();
                $path=$p_info['path'].'-'.$id;
                $cate->where(['id'=>$id])->setField(['path'=>$path]);
            }
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }

        return $this->fetch();
    }
    /**
     * [edit_cate 编辑分类]
     * @return [type] [description]
     *
     */
    public function edit_cate()
    {
        $cate = new \app\admin\model\ArticleCateModel();

        if(request()->isAjax()){

            $param = input('post.');
            $p_info = $cate->where(['id'=>$param['pid']])->find();
            $flag = $cate->editCate($param);
            if($flag){
                $id = $param['id'];
                $path=$p_info['path'].'-'.$id;
                $cate->where(['id'=>$id])->setField(['path'=>$path]);
            }
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }

        $cate_info = $cate->getAllCate();
        $list = $this->cate_tree($cate_info);
        $this->assign('list',$list);
        $id = input('param.id');
        $this->assign('cate',$cate->getOneCate($id));
        return $this->fetch();
    }


    /**
     * [UserDel 删除分类]
     * @return [type] [description]
     *
     */
    public function del_cate()
    {
        $id = input('param.id');
        $cate = new \app\admin\model\ArticleCateModel();
        $flag = $cate->delCate($id);
        return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
    }



    /**
     * [user_state 分类状态]
     * @return [type] [description]
     *
     */
    public function cate_state()
    {
        $id=input('param.id');
        $status = Db::name('article_cate')->where(array('id'=>$id))->value('status');//判断当前状态情况
        if($status==1)
        {
            $flag = Db::name('article_cate')->where(array('id'=>$id))->setField(['status'=>0]);
            return json(['code' => 1, 'data' => $flag['data'], 'msg' => '已禁止']);
        }
        else
        {
            $flag = Db::name('article_cate')->where(array('id'=>$id))->setField(['status'=>1]);
            return json(['code' => 0, 'data' => $flag['data'], 'msg' => '已开启']);
        }
    
    }
    /*获取无限极分类列表*/
    public function cate_tree($cate_info){
        /*无限极分类*/
        $nav = new \org\Leftnav;
        $list = $nav::rule($cate_info);
        return $list;
    }

}
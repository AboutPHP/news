<?php
/**
 * Created by PhpStorm.
 * User: sunzhaoquan
 * Date: 2017/6/26
 * Time: 11:36
 */
namespace app\index\controller;
use app\admin\model\ArticleErrorModel;
use app\admin\model\ArticleModel;
use app\admin\model\ArticleReportModel;
use app\admin\model\ArticleCateModel;
use app\admin\model\CommentModel;
use app\admin\model\ReportModel;
use app\user\model\CollectionModel;
use think\Controller;
use think\Db;
use app\index\controller\index;
use think\exception\PDOException;
use think\Request;
use app\common\Base;
class News extends Base
{
    /**
     * [index  新闻列表]
     * @by author sunzhaoquan
     */

    public function index(){
        $ArticleCate = new ArticleCateModel();
        $cate_info = $ArticleCate->getCateByStatus();
        $right_info = $this->views();
        $this->assign([
            'right_info'=>$right_info,
            'cate_info'=>$cate_info,
        ]);
        return view('list');
    }

    /**
     * [detail  新闻详情]
     * @by author zhangzheng
     */
    public function detail($id){
        $Article = new ArticleModel();
        $article = $Article->getOneArticle($id);
        $Collection = new CollectionModel();
        $map = array('user_id'=>1,'article_id'=>$id);
        $collection = $Collection->where($map)->find();
        $right_info = $this->views();
        $comments = $this->comments($id);
        $this->assign([
            'right_info'=>$right_info,
            'article'=>$article,
            'collection'=>$collection,
            'comments'=>$comments
        ]);
        return $this->fetch('detail');
    }
    /**
     *[views 点击量最多的10调新闻]
     */
    public function views(){
        $im = new ArticleModel();
        $top_info = $im->top_article();
        return $top_info;
    }
    public function zan(){
        $id=intval(input('id'));
        $Comment = new CommentModel();
        try{
            $map = array('id'=>$id);
            $comment = $Comment->where($map)->setInc('zan');
        }catch (PDOException $e){
            return json(['code' => 0, 'data' => '', 'msg' => $e->getMessage()]);
        }
        return json(['code' => 1, 'data' => $comment, 'msg' => '点赞成功']);
    }


    /**
     * [comments  根据文章ID，返回json格式的评论信息]
     * @return \think\response\Json
     * @by author wangchanghao
     */
    public function comments($id)
    {
        $config['page'] = input('get.page',1);
        $limit = input('get.limit',10);
        $map['article_id'] = $id;
        $Comment = new CommentModel();
        $comments = $Comment->getCommentByWhere($map,$limit,$config);
        if(request()->isAjax()){
            return json($comments);
        }
        return $comments;
    }

    /**
     * [addComment  添加评论 返回json数据]
     * @return \think\response\Json
     * @by author wangchanghao
     */
    public function addComment(){
        $Comment = new CommentModel();
        $param = input('post.');
        try{
            $id = $Comment->insertGetId($param);
            $param['id'] =  $id;
            $param['create_time'] =  date("Y-m-d H:i:s");
            $param['zan'] = 0;
            $param['uid'] = 1;
        }catch (PDOException $e){
            return json(['code' => 0, 'data' => '', 'msg' => $e->getMessage()]);
        }
        return json(['code' => 1, 'data' => array($param), 'msg' => '评论成功']);
    }
    /**
     * [add_col  添加收藏]
     * @by author sunzhaoquan
     */
    public function add_col(){

        $article_id = intval(input('post.article_id'));
        $user_id = 1;//此user_id是通过登录后的user_id；
        $param['user_id'] = $user_id;
        $param['article_id'] = $article_id;
        if(request()->isAjax()){
            $Collection = new CollectionModel();
            $collection = $Collection->where($param)->find();
            $param['status'] = intval(input('post.status'));
            if($collection != false){
                $collection = $Collection->updateField($collection['id'],$param['status']);
                return json(['code' => $collection['code'], 'data' => $collection['data'], 'massages' => $collection['msg']]);
            }else{
                $collection = $Collection->add_collection($param);
                return json(['code' => $collection['code'], 'data' => $collection['data'], 'massages' => $collection['msg']]);
            }
        }
    }

    /**
     * 评论举报
     * @return \think\response\Json
     */
    public function yanlunJB(){
        $param = input('post.');
        $Report = new ReportModel();
        $param['uid']=1;
        $param['ip']=request()->ip();
        $Comment = new CommentModel();
        $comment = $Comment->where('id',$param['comment_id'])->find();
        if (empty($comment)){
            return json(['code' => 0, 'data' => '', 'massages' =>'评论已经被删除，请重新加载页面！' ]);
        }
        try{
            $Report->save($param);
        }catch (PDOException $e){
            if ($e->getCode() == 10501){
                $massages ="不可以重复操作，谢谢您的支持！";
            }else{
                $massages ="举报失败，请您重试！";
            }
            return json(['code' => 0, 'data' => '', 'massages' =>$massages ]);
        }
        return json(['code' => 1, 'data' => $param, 'massages' => '举报成功，谢谢您的支持！']);
    }

    /**
     * 文章举报
     * @return \think\response\Json
     */
    public function articleJB(){
        $param = input('post.');
        $ArticleRepor = new ArticleReportModel();
        $param['uid']=1;
        $param['ip']=request()->ip();
        $Article = new ArticleModel();
        $article = $Article->where('id',$param['article_id'])->find();
        if (empty($article)){
            return json(['code' => 0, 'data' => '', 'massages' =>'新闻已经被删除，请重新加载页面！' ]);
        }
        try{
            $ArticleRepor->save($param);
        }catch (PDOException $e){
            if ($e->getCode() == 10501){
                $massages ="不可以重复操作，谢谢您的支持！";
            }else{
                $massages ="举报失败，请您重试！";
            }
            return json(['code' => 0, 'data' => '', 'massages' =>$massages ]);
        }
        return json(['code' => 1, 'data' => $param, 'massages' => '举报成功，谢谢您的支持！']);
    }

    /**
     * 文章纠错
     * @return \think\response\Json
     */
    public function articleJC(){
        $param = input('post.');
        $ArticleError = new ArticleErrorModel();
        $Article = new ArticleModel();
        $article = $Article->where('id',$param['article_id'])->find();
        if (empty($article)){
            return json(['code' => 0, 'data' => '', 'massages' =>'新闻已经被删除，请重新加载页面！' ]);
        }
        try{
            $ArticleError->save($param);
        }catch (PDOException $e){
            return json(['code' => 0, 'data' => '', 'massages' =>$e->getMessage() ]);
        }
        return json(['code' => 1, 'data' => $param, 'massages' => '谢谢您的支持！']);
    }

}











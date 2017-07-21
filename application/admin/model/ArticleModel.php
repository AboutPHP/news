<?php

namespace app\admin\model;
use think\Db;
use think\model\Merge;

class ArticleModel extends Merge
{
    protected $name = "article";
    
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = true;

    // 定义关联模型列表
    protected $relationModel = ['ArticleReport','ArticleError'];
    // 定义关联外键
    protected $fk = 'article_id';

    public function comments()
    {
        return $this->hasMany('CommentModel')->field('content,uid,id,create_time,update_time,article_id,zan');
    }

    /**
     * @param $map 条件
     * @param $limit 每页显示数量
     * @param $config 分页配置信息
     * @return array 返回分页信息和对象数组
     */
    public function getArticleByWhere($map, $limit, $config)
    {
$result = $this
->field('
            `news_article`.`id`,
            `news_article`.`title`,
            `news_article`.`cate_id`,
            `news_article`.`photo`,
            `news_article`.`remark`,
            `news_article`.`keyword`,
            `news_article`.`views`,
            `news_article`.`from`,
            `news_article`.`writer` ,
            `news_article`.`create_time`,
            `news_article`.`update_time`,
            `news_article`.`status`,
            `news_article`.`is_tui`,
            `news_article`.`sort`,
            `news_article_cate`.`name`')
->join('news_article_cate','news_article.cate_id = news_article_cate.id')
->where($map)
->paginate($limit,false,$config)
->toArray();
$result['pages']= intval(ceil($result['total']/$limit));
return $result;
}
    
    
    /**
     * [insertArticle 添加文章]
     *
     */
    public function insertArticle($param)
    {
        try{
            unset($param['file']);
            $result =$this->insert($param);
            if(false === $result){
                return ['code' => -1, 'data' => '', 'msg' => $this->getError()];
            }else{
                return ['code' => 1, 'data' => '', 'msg' => '文章添加成功'];
            }
        }catch( PDOException $e){
            return ['code' => -2, 'data' => '', 'msg' => $e->getMessage()];
        }
    }



    /**
     * [editArticle 编辑文章]
     *
     */
    public function editArticle($param)
    {
        try{
            unset($param['file']);
            $result = $this->update($param, ['id' => $param['id']]);
            if(false === $result){          
                return ['code' => 0, 'data' => '', 'msg' => $this->getError()];
            }else{
                return ['code' => 1, 'data' => '', 'msg' => '文章编辑成功'];
            }
        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    /**
     * [editArticle 编辑文章]
     *
     */
    public function editArticleField($param,$map)
    {
        try{
            $result = $this->where($map)->setField($param);
            if(false === $result){
                return ['code' => 0, 'data' => '', 'msg' => $this->getError()];
            }else{
                return ['code' => 1, 'data' => '', 'msg' => '文章编辑成功'];
            }
        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }



    /**
     * [getOneArticle 根据文章id获取一条信息]
     *
     */
    public function getOneArticle($id)
    {
        return $this->where('id', $id)->find();
    }



    /**
     * [delArticle 删除文章]
     *
     */
    public function delArticle($id)
    {
        try{
            $this::destroy($id);
            return ['code' => 1, 'data' => '', 'msg' => '删除文章成功'];
        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    /**
     * [arts_info 前台获取对应分类信息下的新闻列表]
     * @param cate_id
     * @author sunzhaoquan
     * */
    public function arts_info($cate_id){
        return $this->where(['cate_id'=>$cate_id])->field('id,title,photo,remark,create_time')->select();
    }
    public function news_info(){
        return $this->field('id,title,photo,remark,create_time')->order('id desc')->limit(4)->select();
    }

    /**
     * [cate_all_info 前台获取全部的分类信息]
     * @author sunzhaoquan
     * */
    public function cate_all_info(){
        return Db::name('article_cate')->where(['status'=>1])->field('id,name')->select();
    }
    /**
     *[commend_info 首页推荐新闻信息]
     * @author sunzhaoquan
     * */
    public function commend_info(){
        return $this->where(['is_tui'=>1])->field('id,title,photo,remark,create_time')->limit(4)->select();
    }
    /**
     *[top_article 首页推荐新闻信息]
     * @author sunzhaoquan
     * */
    public function top_article(){
        return $this->field('id,title,create_time')->order('views desc,id desc')->limit(10)->select();
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: sunzhaoquan
 * Date: 2017/7/17 0007
 * TIME:15:03
 */
namespace app\user\model;
use think\Db;
use think\Model;
use app\admin\model\ArticleModel;
class ArticleErrorModel extends Model{
    protected $name = "article_error";

    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = true;


    /**
     * @param $map 条件
     * @param $limit 每页显示数量
     * @param $config 分页配置信息
     * @return array 返回分页信息和对象数组
     */
    public function getArticleByWhere($limit , $config)
    {
        $result = $this->field('news_article_error.*,news_article.title')
            ->join('news_article','news_article.id = news_article_error.article_id')
            ->order('article_id desc')
            ->paginate($limit,false,$config)
            ->toArray();
        $result['pages']= intval(ceil($result['total']/$limit));
        return $result;
    }
    /**
     * [editArticle 编辑文章]
     *
     */
    public function editArticle($param)
    {

        try{
            Db::name('article')->where(['id'=>$param['art_id']])->update(['title'=>$param['title'],'status'=>1,'content'=>$param['content']]);
            $status_info = Db::name('article_error')->where(['id'=>$param['error_id']])->delete();
            if(false === $status_info){
                return ['code' => 0, 'data' => '', 'msg' => $this->getError()];
            }else{
                return ['code' => 1, 'data' => '', 'msg' => '纠错成功'];
            }
        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

}
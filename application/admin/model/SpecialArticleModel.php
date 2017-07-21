<?php

namespace app\admin\model;
use think\Db;
use think\model\Merge;

class SpecialArticleModel extends Merge
{
    protected $name = "special_article";
    protected $autoWriteTimestamp = true;

    public function Special()
    {
        return $this->belongsTo('SpecialModel');
    }

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

    public function insertSpecialArticle($param)
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

    public function getOneArticle($id)
    {
        return $this->where('id', $id)->find();
    }

}
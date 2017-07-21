<?php
/**
 * Created by PhpStorm.
 * User: sunzhaoquan
 * Date: 2017/7/7 0007
 * TIME:15:03
 */
namespace app\user\model;
use think\Db;
use think\Model;
class CollectionModel extends Model{
    protected $name = "collection";

    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = true;


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
            `news_collection`.`id`,
            `news_collection`.`user_id`,
            `news_collection`.`article_id`,
            `news_collection`.`create_time`,
            `news_collection`.`status`,
            `news_article`.`title`,
            `news_article`.`cate_id`,
            `news_article`.`photo`,
            `news_article`.`content`,
            `news_article`.`views`,
            `news_article`.`type`,
            `news_article`.`from`,
            `news_article`.`writer` ,
            `news_article`.`sort`')
            ->join('news_article','news_collection.article_id = news_article.id')
            ->where($map)
            ->paginate($limit,false,$config)
            ->toArray();
        $result['pages']= intval(ceil($result['total']/$limit));
        return $result;
    }

    /**
     * [editArticle 编辑文章]
     *@by author sunzhaoquan
     */
    public function updateField($id,$status)
    {
        try{
            if ($status){
                $this->where(['id'=>$id])->setField('status',0);
                return ['code' => 1, 'data' => 0, 'msg' => '取消收藏成功'];
            }else{
                $this->where(['id'=>$id])->setField('status',1);
                return ['code' => 1, 'data' => 1, 'msg' => '收藏成功'];
            }
        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }
    /**
     * [add_collection 添加收藏]
     *
     */
    public function add_collection($param)
    {
        try{
            $this->allowField(true)->save($param);
            return ['code' => 1, 'data' => 1, 'msg' => '添加收藏成功'];
        }catch( PDOException $e){
            return ['code' => -2, 'data' => '', 'msg' => $e->getMessage()];
        }
    }
}
<?php
namespace app\admin\model;
use think\Model;

/**
 * Class ReportModel 举报
 * @package app\admin\model
 */
class ReportModel extends Model{

    protected $name = "report";
    protected $autoWriteTimestamp = true;

    /**
     * @param $map 条件
     * @param $limit 每页显示数量
     * @param $config 分页配置信息
     * @return array 返回分页信息和对象数组
     */
    public function getReportByWhere($map, $limit, $config)
    {
        $result = $this
            ->field('
            `news_report`.`id`,
            `news_report`.`uid`,
            `news_report`.`content`,
            `news_report`.`status`,
            `news_report`.`ip`,
            `news_report`.`create_time`,
            `news_report`.`update_time`,
            `news_report`.`comment_id`,
            `news_comment`.`article_id`')
            ->where($map)
            ->join('news_comment','news_comment.id = news_report.comment_id')
            ->group('news_report.comment_id')
            ->paginate($limit,false,$config)
            ->toArray();
        $result['pages']= intval(ceil($result['total']/$limit));
        return $result;
    }

    public function editReport($param)
    {
        try{
            $result =  $this->save($param, ['id' => $param['id']]);
            if(false === $result){
                return ['code' => 0, 'data' => '', 'msg' => $this->getError()];
            }else{
                return ['code' => 1, 'data' => '', 'msg' => '编辑菜单成功'];
            }
        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    public function insertReport($param)
    {
        try{
            $result = $this->save($param);
            if(false === $result){
                return ['code' => -1, 'data' => '', 'msg' => $this->getError()];
            }else{
                return ['code' => 1, 'data' => '', 'msg' => '添加菜单成功'];
            }
        }catch( PDOException $e){
            return ['code' => -2, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    /**
     * 删除同一评论的举报信息
     * @param $comment_id
     * @return array
     */
    public function delReport($comment_id)
    {
        try{
            $this->where('comment_id', $comment_id)->delete();
            return ['code' => 1, 'data' => '', 'msg' => '删除举报成功'];
        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    public function getOneReport($id)
    {
        return $this->where('id', $id)->find();
    }
}

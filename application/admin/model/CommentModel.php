<?php
namespace app\admin\model;
use think\model\Merge;

/**
 * Class Comment 评论
 * @package app\admin\model
 */
class CommentModel extends Merge
{
    protected $autoWriteTimestamp = true;
    protected $table = 'news_comment';
    // 定义关联模型列表
    protected $relationModel = ['Report'];
    // 定义关联外键
    protected $fk = 'comment_id';


    public function insertComment($param)
    {
        if (input('post.')) {
            $result = $this->allowField(true)->save($param);
        }
    }

    /**
     * @param $map 条件
     * @param $limit 每页显示数量
     * @param $config 分页配置信息
     * @return array 返回分页信息和对象数组
     */
    public function getCommentByWhere($map, $limit, $config)
    {
        $result = $this
            ->field('*')
            ->where($map)
            ->order('id desc')
            ->paginate($limit,false,$config)
            ->toArray();
        $result['pages']= intval(ceil($result['total']/$limit));
        return $result;
    }

    public function getallComment($id)
    {
        return $this->select();
    }
}

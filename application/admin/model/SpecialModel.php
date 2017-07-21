<?php

namespace app\admin\model;
use think\model\Merge;

class SpecialModel extends Merge
{
    protected $name = "special";

    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = true;

    public function SpecialArticle()
    {
        return $this->hasMany('SpecialArticleModel','special_id');//hasMany是一对多
    }

    /**
     * @param $map 条件
     * @param $limit 每页显示数量
     * @param $config 分页配置信息
     * @return array 返回分页信息和对象数组
     */
    public function getSpecialByWhere($map, $limit, $config)
    {
        $result = $this->field('
            `news_special`.`id`,
            `news_special`.`title`,
            `news_special`.`photo`,
            `news_special`.`remark`,
            `news_special`.`keyword`,
            `news_special`.`views`,
            `news_special`.`writer` ,
            `news_special`.`create_time`,
            `news_special`.`update_time`,
            `news_special`.`is_tui`,
            `news_special`.`sort`')
            ->where($map)->order('id desc')
            ->paginate($limit,false,$config)
            ->toArray();
            $result['pages']= intval(ceil($result['total']/$limit));
            return $result;
    }
    
    
    /**
     * [insertArticle 添加专题]
     *
     */
    public function insertSpecial($param)
    {
        try{
            unset($param['file']);
            $param['create_time'] = time();
            $param['update_time'] = time();
            $result =$this->insert($param);
            if(false === $result){
                return ['code' => -1, 'data' => '', 'msg' => $this->getError()];
            }else{
                return ['code' => 1, 'data' => '', 'msg' => '专题添加成功'];
            }
        }catch( PDOException $e){
            return ['code' => -2, 'data' => '', 'msg' => $e->getMessage()];
        }
    }



    /**
     * [editArticle 编辑专题]
     */
    public function editSpecial($param)
    {
        try{
            unset($param['file']);
            $result = $this->update($param, ['id' => $param['id']]);
            if(false === $result){          
                return ['code' => 0, 'data' => '', 'msg' => $this->getError()];
            }else{
                return ['code' => 1, 'data' => '', 'msg' => '专题编辑成功'];
            }
        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    /**
     * [getOneArticle 根据专题id获取一条信息]
     */
    public function getOneSpecial($id)
    {
        $result = $this->where('id', $id)->find();
        return $result;
    }

}
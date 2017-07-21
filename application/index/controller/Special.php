<?php
namespace app\index\controller;
use app\admin\model\SpecialArticleModel;
use app\admin\model\SpecialModel;
use app\common\Base;

/**
 * 专题模块
 * Class Special
 * @package app\index\controller
 */
class Special extends Base
{
    /**
     * 根据专题唯一主键关联查询专题文章
     * 根据专题别名动态渲染页面
     * @param $id 专题主键
     */
    public function index($id){
        $Special = new SpecialModel();

        $special = $Special->with('SpecialArticle')->find($id)->toArray();

        $special['keyword'] = json_decode($special['keyword'],true);//格式化专题中的分类


        $special['special_article'] = $this->array_group($special['special_article'],'type');//筛选后的数据
        return json($special);
//        $this->assign('special',$special);
//        return view('food');
    }

    /**
     * @param $array
     * @param $key 根据某个元素筛选
     * @return mixed
     */
    public function array_group($array,$key){
        foreach($array as $k=>$v){
            $result[$v[$key]][]=$v;
        }
        return $result;
    }

    public function test(){
        $SpecialModel = new SpecialModel();
        $list = $SpecialModel->with('SpecialArticle')->find('800')->toArray();
        return json($list);
    }

}











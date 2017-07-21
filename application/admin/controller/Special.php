<?php

namespace app\admin\controller;
use app\admin\model\SpecialArticleModel;
use app\admin\model\SpecialModel;
use think\exception\PDOException;

class Special extends Base
{
    /**
     * [index 专题列表]
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
            $Special = new SpecialModel();
            $result = $Special->getSpecialByWhere($map,10,$config);
            return json($result);
        }
        return $this->fetch();
    }


    /**
     * [userAdd 添加专题]
     */
    public function insertSpecial()
    {
        $Special = new SpecialModel();
        if(request()->isAjax()){
            $param = input('post.');
            $flag = $Special->insertSpecial($param);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
        return $this->fetch('special/add_special');
    }

    /**
     * [editSpecial 修改专题]
     *
     */
    public function editSpecial(){
        $Special = new SpecialModel();
        if (request()->isAjax()){
            $param = input('post.');
            $flag = $Special->editSpecial($param,['id' => $param['id']]);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
        $id = input('param.id');
        $this->assign('special',$Special->getOneSpecial($id));
        return $this->fetch('special/edit_special');
    }

    /**
     * [delSpecial 删除专题]
     */
    public function delSpecial ()
    {
        $id = input('id');
        $Special = new SpecialModel();
        try{
            $Special->where('id',$id)->delete();
        }catch (PDOException $e){
            return json(['code' => 0, 'data' => '', 'msg' => $e->getMessage()]);
        }
        return json(['code' => 1, 'data' => '', 'msg' => '删除成功']);
    }

    /**
     * [is_tui 专题推荐为]
     */
    public function is_tui()
    {
        $id = input('param.id');
        $Special = new SpecialModel();
        $status = $Special->where(array('id'=>$id))->value('is_tui');//判断当前状态情况
        if($status==1)
        {
            $flag = $Special->where(array('id'=>$id))->setField(['is_tui'=>0]);
            return json(['code' =>0, 'data' => $flag['data'], 'msg' => '不推荐']);
        }else {
            $flag = $Special->where(array('id'=>$id))->setField(['is_tui'=>1]);
            return json(['code' => 1, 'data' => $flag['data'], 'msg' => '推荐']);
        }
    }

    /**
     * [insertSpecialArticle 添加专题文章]
     */
    public function insertSpecialArticle(){

        $SpecialArticle = new SpecialArticleModel();
        $Special = new SpecialModel();
        if(request()->isAjax()){
            $param = input('post.');
            $param['type'] = $param['special_id'];
            $flag = $SpecialArticle->insertSpecialArticle($param);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
        $specials = $Special->select();
        foreach ($specials as $k => $v) {
            $specials[$k]['keyword'] = json_decode($v['keyword'],true);
        }
        $this->assign('specials',$specials);
        return $this->fetch('special/add_special_article');
    }
}
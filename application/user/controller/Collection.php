<?php
/**
 * Created by PhpStorm.
 * User: sunzhaoquan
 * Date: 2017/7/7 0007
 * TIME:14:07
 */
namespace app\user\controller;
use app\user\model\CollectionModel;
use app\common\Base;
use think\Request;
class Collection extends Base
{
    /**
     * [index  我的收藏]
     * @by author wangchanghao
     * */
    public function index(){
        $status = input('status',1); //收藏状态

        $map['news_collection.status'] = $status;

        $config['page'] = input('get.page') ? input('get.page'):1;
        $this->assign('status', $status);
        if(input('get.page'))
        {
            $limit = 4;
            $Article = new CollectionModel();
            $result = $Article->getArticleByWhere($map, $limit ,$config);
            return json($result);
        }
        return view('index');
    }
    /**
     * [edit_status  取消收藏]
     * @by author sunzhaoquan
     * */
    public function edit_status(Request $request){
        if (request()->isAjax()){
            $Article = new CollectionModel();
            $id = $request->instance()->param('id');
            $status = '0';
            $flag = $Article->editArticleField($id,$status);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }

        return view('index');
    }
}
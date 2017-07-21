<?php
namespace app\admin\controller;
use app\admin\model\CommentModel;
use app\admin\model\ReportModel;
use think\Exception;

class Report extends Base
{
    public function index()
    {
        $key = input('key');
        $map = [];
        if($key&&$key!==""){
            $map['news_report.content'] = ['like',"%" . $key . "%"];
        }
        $config['page'] = input('get.page') ? input('get.page'):1;
        if(input('get.page')){
            $Report = new ReportModel();
            $result = $Report->getReportByWhere($map,10,$config);
            return json($result);
        }
        $this->assign('val', $key);
        return $this->fetch();
    }


    /**
     * 编辑举报信息
     * @return mixed|\think\response\Json
     */
    public function edit_report()
    {
        $Report = new \app\admin\model\ReportModel();
        if(request()->isPost()){
            $param = input('post.');
            $flag = $Report->editReport($param);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
        $id = input('param.id');
        $this->assign('report',$Report->getOneReport($id));
        return $this->fetch();
    }


    /**
     * [add_rule 添加举报]
     */
    public function add_report()
    {
        if(request()->isAjax()){
            $param = input('post.');
            $report = new \app\admin\model\ReportModel();
            $flag = $report->insertReport($param);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
        return $this->fetch();
    }

    /**
     * 删除举报信息
     * @return \think\response\Json
     */
    public function ReportDel()
    {
        $id = input('param.comment_id');
        $role = new \app\admin\model\ReportModel();
        $flag = $role->delReport($id);
        return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
    }

    /**
     * 删除评论信息及举报信息
     * @return array|\think\response\Json
     */
    public function commentDel()
    {
        $comment_id = input('param.comment_id');
        try{
            CommentModel::destroy($comment_id);
        }catch (Exception $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
        return json(['code' => 1, 'data' => '', 'msg' => '评论删除成功']);
    }
}

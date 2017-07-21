<?php
namespace app\admin\controller;
use app\admin\model\ArticleReportModel;
use think\Exception;

class ArticleReport extends Base
{
    public function index()
    {
        $map = [];
        $ArticleReport = new ArticleReportModel();
        $result = $ArticleReport->getArticleReportByWhere($map);
        $this->assign('data', $result);
        return $this->fetch();
    }


    public function articleReportDel(){
        $id = input('id');
        $ArticleReport = new ArticleReportModel();
        try{
            $ArticleReport->where('article_id',$id)->delete();
        }catch (Exception $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
        return ['code' => 1, 'data' => '', 'msg' => '删除成功'];
    }
}

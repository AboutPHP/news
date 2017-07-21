<?php
/**
 * Created by PhpStorm.
 * User: sunzhaoquan
 * Date: 2017/7/17 0017
 * TIME:14:36
 */
namespace app\user\controller;
use app\user\model\ArticleErrorModel;
use app\common\Base;
use think\Request;
class ArticleError extends Base
{
    public function index()
    {
        $config['page'] = input('get.page') ? input('get.page') : 1;
        if (input('get.page')) {
            $limit = 14;
            $error = new ArticleErrorModel();
            $result = $error->getArticleByWhere($limit, $config);
            return json($result);
        }
        return view('index');
    }
    /**
     * [modify 修改新闻]
     * @by author sunzhaoquan
     * */
    public function modify(Request $request){
        $cout = new \app\admin\model\ArticleModel();
        $error = new ArticleErrorModel();
        if (request()->isAjax()){
            $param = input('post.');
            $flag = $error->editArticle($param);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }else {
            $art_id = $request->instance()->param('art_id');
            $error_id = $request->instance()->param('id');
            $article_info = $cout->getOneArticle($art_id);
            $article_info['error_id']=$error_id;
            $this->assign('article', $article_info);
            return view('modify');
        }
    }
}
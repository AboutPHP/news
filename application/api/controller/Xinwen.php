<?php
/**
 * [Xinwen 衔接用户中心新闻列表类]
 * User: sunzhaoquan
 * Date: 2017/7/10 0010
 * TIME:11:24
 */
namespace app\api\controller;
use app\admin\model\ArticleModel;

class Xinwen{

    /**
     * [top_art 获取新闻置顶文章]
     * @param int $limit 查询数量
     * @return \think\response\Jsonp
     * @author sunzhaoquan
     *
     */
    public function top_art($limit=3){
        $Article = new ArticleModel();
        $map = array('is_tui'=>1,'status'=>2);
        $articles = $Article
            ->field('id,title,photo')
            ->where($map)
            ->order('sort DESC')
            ->limit(0,$limit)->select();
        $url ="http://".$_SERVER['HTTP_HOST'];
        foreach ($articles as $key => $value){
            $articles[$key]['photo'] = $url . '/uploads/images/'.$value['photo'];
        }
        return jsonp(['code' => 200, 'data' =>$articles , 'msg' => '置顶新闻']);
    }
    /**
     * [detail 获取具体文章的详情]
     * @param $id 新闻主键
     * @return \think\response\Jsonp
     * @author sunzhaoquan
     */
    public function detail($id){
        $Article = new ArticleModel();
        $map = array('id'=>$id,'status'=>2);
        $article = $Article->field('id,title,photo,remark,keyword,content,views,from,writer,create_time,update_time')->where($map)->find();
        if (empty($article)){
            return jsonp(['code' => 300, 'data' => '', 'msg' => '新闻已经删除！']);
        }
        $url ="http://".$_SERVER['HTTP_HOST'];
        $article['content'] = $this->replacePicUrl($article['content'],$url);
        $article['photo'] = $url.'/uploads/images/'.$article['photo'];
        return jsonp(['code' => 200, 'data' =>$article , 'msg' => '新闻详情']);
    }

    /**
     * [news 新闻列表接口]
     * @param int $limit 分页数
     * @param int $page 当前页
     * @param int $status 新闻状体 默认为 2  已发布状体
     * @return \think\response\Jsonp 放回jsonp数据格式
     * @author sunzhaoquan
     */
    public function news($limit = 5,$page = 1){
        $map =  array('status'=>2);
        $config['page'] = $page;
        $Article = new ArticleModel();
        $result = $Article->field('id,title,photo,remark,keyword,views,from,writer,create_time,update_time')->where($map)->paginate($limit,false,$config)->toArray();
        $url ="http://".$_SERVER['HTTP_HOST'];
        foreach ($result['data'] as $key => $value){
            $result['data'][$key]['photo'] = $url . '/uploads/images/'.$value['photo'];
        }
        return jsonp(['code' => 200, 'data' =>$result , 'msg' => '新闻列表']);
    }






























    /**
     * 为图片地址添加域名地址
     * @param  string $content 要替换的内容
     * @param  string $strUrl 内容中图片要加的域名
     * @return string
     * @eg
     */
    function replacePicUrl($content = null, $strUrl = null) {
        if ($strUrl) {
            //提取图片路径的src的正则表达式 并把结果存入$matches中
            preg_match_all("/<img(.*)src=\"([^\"]+)\"[^>]+>/isU",$content,$matches);
            $img = "";
            if(!empty($matches)) {
                //注意，上面的正则表达式说明src的值是放在数组的第三个中
                $img = $matches[2];
            }else {
                $img = "";
            }
            if (!empty($img)) {
                $patterns= array();
                $replacements = array();
                foreach($img as $imgItem){
                    $final_imgUrl = $strUrl.$imgItem;
                    $replacements[] = $final_imgUrl;
                    $img_new = "/".preg_replace("/\//i","\/",$imgItem)."/";
                    $patterns[] = $img_new;
                }

                //让数组按照key来排序
                ksort($patterns);
                ksort($replacements);

                //替换内容
                $vote_content = preg_replace($patterns, $replacements, $content);

                return $vote_content;
            }else {
                return $content;
            }
        } else {
            return $content;
        }
    }
}

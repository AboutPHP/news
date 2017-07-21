<?php
namespace app\admin\model;
use think\Model;

/**
 * Class ReportModel 文章举报
 * @package app\admin\model
 */
class ArticleReportModel extends Model{
    protected $name = "article_report";
    protected $autoWriteTimestamp = true;


    public function getArticleReportByWhere($map)
    {
        $result = $this
            ->field('
            `news_article_report`.`id`,
            `news_article_report`.`uid`,
            `news_article_report`.`content`,
            `news_article_report`.`status`,
            `news_article_report`.`ip`,
            `news_article_report`.`create_time`,
            `news_article_report`.`update_time`,
            `news_article_report`.`article_id`,
            `news_article_report`.`report`,
            `news_article`.`title`,
            `news_article`.`id`')
            ->where($map)
            ->join('news_article','news_article.id = news_article_report.article_id')
            ->group('news_article_report.article_id')
            ->select();
        return $result;
    }
}

<?php
namespace app\mobile\controller;

use think\Controller;

class Index extends Controller
{
    public function index(){
        return view('index');
    }
    public function news_detail(){
        return view('news_detail');
    }
}
<?php
defined('WEB_URL') or define('WEB_URL','http://www.shop.com/');
return array(
    'TMPL_PARSE_STRING'=>array(
        '__CSS__'=>WEB_URL.'Public/Home/css',
        '__JS__'=>WEB_URL.'Public/Home/js',
        '__IMG__'=>WEB_URL.'Public/Home/images',
        '__BRAND__'=>'http://brand-logo.b0.upaiyun.com/', //代表brand_logo空间的域名
        '__GOODS__'=>'http://itsource-goods.b0.upaiyun.com/', //代表brand_logo空间的域名
        '__UEDITOR__'=>WEB_URL.'Public/Home/ueditor',
    ),
);
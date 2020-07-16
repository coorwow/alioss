<?php
/*
 * @Author: Mr.snail 
 * @Date: 2020-07-13 15:12:05 
 * @Last Modified by: Mr.snail
 * @Last Modified time: 2020-07-13 15:13:02
 */
return [
    'accessKey'                 => [// 在后台插件配置表单中的键名 ,会是config[text]
        'title'   => 'AccessKeyId', // 表单的label标题
        'type'    => 'text',// 表单的类型：text,password,textarea,checkbox,radio,select等
        'value'   => '',// 表单的默认值
        "rule"    => [
            "require" => true
        ],
        "message" => [
            "require" => 'AccessKey不能为空'
        ],
        'tip'     => '<a target="_blank" href="https://help.aliyun.com/document_detail/31827.html?spm=a2c4g.11186623.6.592.74b57f85TOyEWp#title-3qf-u3w-nsp" target="_blank">查看阿里云对象存储基本概念</a>' //表单的帮助提示
    ],
    'secretKey'                 => [// 在后台插件配置表单中的键名 ,会是config[password]
        'title'   => 'AccessKeySecret',
        'type'    => 'password',
        'value'   => '',
        "rule"    => [
            "require" => true
        ],
        "message" => [
            "require" => 'SecretKey不能为空'
        ],
        'tip'     => ''
    ],
    'protocol'                  => [// 在后台插件配置表单中的键名 ,会是config[select]
        'title'   => '域名协议',
        'type'    => 'select',
        'options' => [//select 和radio,checkbox的子选项
            'http'  => 'http',// 值=>显示
            'https' => 'https',
        ],
        'value'   => 'http',
        "rule"    => [
            "require" => true
        ],
        "message" => [
            "require" => '域名协议不能为空'
        ],
        'tip'     => ''
    ],
    'domain'                    => [
        'title'   => '空间域名',
        'type'    => 'text',
        'value'   => '',
        
        'tip'     => ''
    ],
    'bucket'                    => [
        'title'   => '存储空间',
        'type'    => 'text',
        'value'   => '',
        "rule"    => [
            "require" => true
        ],
        "message" => [
            "require" => '空间名称不能为空'
        ],
        'tip'     => ''
    ],
    'region'                      => [// 在后台插件配置表单中的键名 ,会是config[select]
        'title'   => '存储区域',
        'type'    => 'select',
        'options' => [//select 和radio,checkbox的子选项
            'oss-cn-hangzhou'  => '华东1（杭州）',// 值=>显示
            'oss-cn-shanghai'  => '华东2（上海）',
            'oss-cn-qingdao'  => '华北1（青岛）',
            'oss-cn-beijing' => '华北2（北京）',
            'oss-cn-zhangjiakou' => '华北 3（张家口）',
            'oss-cn-huhehaote' => '华北5（呼和浩特）',
            'oss-cn-wulanchabu' => '华北6（乌兰察布）',
            'oss-cn-shenzhen' => '华南1（深圳）',
            'oss-cn-heyuan' => '华南2（河源）',
            'oss-cn-chengdu' => '西南1（成都）',
            'oss-cn-hongkong' => '中国（香港）',
            'oss-us-west-1' => '美国西部1（硅谷）',

            'oss-us-east-1' => '美国东部1（弗吉尼亚）',
            'oss-ap-southeast-1' => '亚太东南1（新加坡）',
            'oss-ap-southeast-2' => '亚太东南2（悉尼）',
            'oss-ap-southeast-3' => '亚太东南3（吉隆坡）',
            'oss-ap-southeast-5' => '亚太东南5（雅加达）',
            'oss-ap-northeast-1' => '亚太东北1（日本）',
            'oss-ap-south-1' => '亚太南部1（孟买）',
            'oss-eu-central-1' => '欧洲中部1（法兰克福）',
            'oss-eu-west-1' => '英国（伦敦）',
            'oss-me-east-1' => '中东东部1（迪拜）',
        ],
        'value'   => '',
        "rule"    => [
            "require" => true
        ],
        "message" => [
            "require" => '存储区域不能为空'
        ],
        'tip'     => ''
    ],
    
    'dir'                    => [
        'title'   => '文件前缀',
        'type'    => 'text',
        'value'   => '',
        'tip'     => '若要设置上传到OSS文件的前缀则需要配置此项，否则置空即可.<br/>填写请确保以/结尾，如：project/'
    ],

    'has_cloud'                 => [
        'title'   => '开启云端直传', 
        'type'    => 'radio',
        'options' => [
            '0' => '不开启',
            '1' => '开启',
        ],
        'value'   => '0',// 表单的默认值
        "rule"    => [
            "require" => true
        ],
        "message" => [
            "require" => '开启云端直传不能为空'
        ],
        'tip'     => '云端直传可提高上传速度，在本地服务器带宽不充足的情况下开启，可明显提高上传速度' //表单的帮助提示
    ],

    'cloud_manger'                 => [
        'title'   => '直传资源管理', 
        'type'    => 'select',
        'options' => [
            '0' => '不记录资源管理',
            '1' => '记录资源管理，不做本地文件存储',
            '2' => '记录资源管理，并做本地文件存储',
        ],
        'value'   => '0',// 表单的默认值
    
        'tip'     => '注意：选择"记录资源管理，不做本地文件存储"时，资源管理中文件会显示 (文件丢失)' //表单的帮助提示
    ],
    'text_watermark'          => [
        'title'   => '水印文字',
        'type'    => 'text',
        'value'   => '',
        'tip'     => ''
    ],
    'styles_watermark'          => [
        'title'   => '水印参数',
        'type'    => 'text',
        'value'   => '',
        'tip'     => '请阅读阿里oss的<a target="_blank" href="https://help.aliyun.com/document_detail/44957.html?spm=a2c4g.11186623.6.1429.c0b979b0HyGYFz">图片水印文档</a>。如：t_50,g_se,color_000000。无需设置text参数。<br/>如图片水印，请不要填写水印文字，在水印参数中设置好水印的各项参数即可。'
    ],
    'styles_avatar'             => [
        'title'   => '样式-头像',
        'type'    => 'explain',
        'value'   => 'avatar',
//        "rule"    => [
//            "require" => true
//        ],
//        "message" => [
//            "require" => '样式-头像不能为空'
//        ],
        'tip'     => '无需设置，系统自动识别，转换成阿里oss参数'
    ],
    'styles_thumbnail120x120'   => [
        'title'   => '样式-缩略图120x120',
        'type'    => 'explain',
        'value'   => 'thumbnail120x120',
//        "rule"    => [
//            "require" => true
//        ],
//        "message" => [
//            "require" => '样式-缩略图120x120不能为空'
//        ],
        'tip'     => '无需设置，系统自动识别，转换成阿里oss参数'
    ],
    'styles_thumbnail300x300'   => [
        'title'   => '样式-缩略图300x300',
        'type'    => 'explain',
        'value'   => 'thumbnail300x300',
//        "rule"    => [
//            "require" => true
//        ],
//        "message" => [
//            "require" => '样式-缩略图300x300不能为空'
//        ],
        'tip'     => '无需设置，系统自动识别，转换成阿里oss参数'
    ],
    'styles_thumbnail640x640'   => [
        'title'   => '样式-缩略图640x640',
        'type'    => 'explain',
        'value'   => 'thumbnail640x640',
//        "rule"    => [
//            "require" => true
//        ],
//        "message" => [
//            "require" => '样式-缩略图640x640不能为空'
//        ],
        'tip'     => '无需设置，系统自动识别，转换成阿里oss参数'
    ],
    'styles_thumbnail1080x1080' => [
        'title'   => '样式-缩略图1080x1080',
        'type'    => 'explain',
        'value'   => 'thumbnail1080x1080',
//        "rule"    => [
//            "require" => true
//        ],
//        "message" => [
//            "require" => '样式-缩略图1080x1080不能为空'
//        ],
        'tip'     => '无需设置，系统自动识别，转换成阿里oss参数'
    ],
];
					
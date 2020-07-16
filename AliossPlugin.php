<?php
/*
 * @Author: Mr.snail 
 * @Date: 2020-07-13 15:13:18 
 * @Last Modified by: Mr.snail
 * @Last Modified time: 2020-07-13 15:17:31
 */
namespace plugins\alioss;

use cmf\lib\Plugin;

class AliossPlugin extends Plugin
{

    public $info = [
        'name'        => 'Alioss',
        'title'       => '阿里OSS存储',
        'description' => '阿里云对象存储OSS',
        'status'      => 1,
        'author'      => 'snail',
        'version'     => '1.0.0'
    ];

    public $hasAdmin = 0;//插件是否有后台管理界面

    // 插件安装
    public function install()
    {
        $storageOption = cmf_get_option('storage');
        if (empty($storageOption)) {
            $storageOption = [];
        }

        $storageOption['storages']['Alioss'] = ['name' => '阿里OSS存储', 'driver' => '\\plugins\\alioss\\lib\\Alioss'];

        cmf_set_option('storage', $storageOption);
        return true;//安装成功返回true，失败false
    }

    // 插件卸载
    public function uninstall()
    {
        $storageOption = cmf_get_option('storage');
        if (empty($storageOption)) {
            $storageOption = [];
        }

        unset($storageOption['storages']['Alioss']);

        cmf_set_option('storage', $storageOption);
        return true;//卸载成功返回true，失败false
    }

    public function fetchUploadView()
    {
        $tab = request()->param('tab');
        $app = request()->param('app');
        if(!$app)
            $app = 'default';
        $config     = $this->getConfig();
        if ($tab == 'cloud') {
            $this->assign("upload_host","{$config['bucket']}.{$config['region']}.aliyuncs.com");
            $this->assign("app",$app);
            $content = $this->fetch('upload');
        } else {

            if($config['has_cloud']=="1")
                $content = "has_cloud_storage";
            else
                $content = "";
        }

        return $content;
    }

    public function cloudStorageTab(&$param)
    {



    }

   

}
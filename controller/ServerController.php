<?php
/*
 * @Author: Mr.snail 
 * @Date: 2020-07-13 15:12:05 
 * @Last Modified by: Mr.snail
 * @Last Modified time: 2020-07-13 15:12:52
 */
namespace plugins\Alioss\controller; 
use cmf\controller\PluginBaseController;
use plugins\alioss\lib\Alioss;
use app\user\model\AssetModel;
use think\Validate;
use \DateTime;
class ServerController extends PluginBaseController
{

    public function getSignature()
    {
        $config = $this->getPlugin()->getConfig();
        $id  = $config['accessKey'];
        $key  = $config['secretKey'];
        $region     = $config['region'];
        $bucket     = $config['bucket'];
        $protocol   = $config['protocol'];
        $dir   = $config['dir'];
        $uploadHost = "{$protocol}://{$bucket}.{$region}.aliyuncs.com";
        $now = time();
        $expire = 30;  //设置该policy超时时间是10s. 即这个policy过了这个有效时间，将不能访问。
        $end = $now + $expire;
        $expiration = $this->gmt_iso8601($end);

        //最大文件大小.用户可以自己设置
        $condition = array(0=>'content-length-range', 1=>0, 2=>1048576000);
        $conditions[] = $condition; 

        // 表示用户上传的数据，必须是以$dir开始，不然上传会失败，这一步不是必须项，只是为了安全起见，防止用户通过policy上传到别人的目录。
        $start = array(0=>'starts-with', 1=>'$key', 2=>$dir);
        $conditions[] = $start; 


        $arr = array('expiration'=>$expiration,'conditions'=>$conditions);
        $policy = json_encode($arr);
        $base64_policy = base64_encode($policy);
        $string_to_sign = $base64_policy;
        $signature = base64_encode(hash_hmac('sha1', $string_to_sign, $key, true));

        //回调
        $callbackUrl = cmf_plugin_url("alioss://Server/callback");
        $callback_param = array('callbackUrl'=>$callbackUrl, 
                 'callbackBody'=>'filename=${object}&size=${size}&mimeType=${mimeType}&height=${imageInfo.height}&width=${imageInfo.width}&', 
                 'callbackBodyType'=>"application/x-www-form-urlencoded");
        $callback_string = json_encode($callback_param);

        $base64_callback_body = base64_encode($callback_string);

        $response = array();
        $response['accessid'] = $id;
        $response['host'] = $uploadHost;
        $response['policy'] = $base64_policy;
        $response['signature'] = $signature;
        $response['expire'] = $end;
        $response['callback'] = "";
        $response['dir'] = $dir;  // 这个参数是设置用户上传文件时指定的前缀。
        return json($response);
    }

    public function callback(){
        //做回调处理 自由编写
        $data = array("Status"=>"Ok");
        return json($data);
    }


    function getUrl()
    {

        $alioss = new Alioss([]);

        $file = $this->request->param('file_path');
        $fileType = $this->request->param('file_type');

        $previewUrl = $fileType == 'image' ? $alioss->getPreviewUrl($file) : $alioss->getFileDownloadUrl($file);
        $url        = $fileType == 'image' ? $alioss->getImageUrl($file, 'watermark') : $alioss->getFileDownloadUrl($file);

        return $this->success('success', null, [
            'url'         => $url,
            'preview_url' => $previewUrl,
            'filepath'    => $file
        ]);
    }


    public function saveFile()
    {
        $config = $this->getPlugin()->getConfig();
        if($config['cloud_manger']<=0)
            return;
        $userId = cmf_get_current_admin_id();
        $userId = $userId ? $userId : cmf_get_current_user_id();
        $alioss = new Alioss([]);
        if (empty($userId)) {
            $this->error('error');
        }
        $validate = new Validate([
            'file_path' => 'require',
            'file_name' => 'require'
        ]);

        $data = $this->request->param();

        $result = $validate->check($data);

        if ($result !== true) {
            $this->error($validate);
        }

        $file_path = $data['file_path'];
        $file_name = $data['file_name'];
        $oss_url = $alioss->getUrl($file_path);
        $oss_info_url = $alioss->getUrl($file_path,'/info');
        $infos = cmf_curl_get($oss_info_url);
        $infos_arr = json_decode($infos);
        $arrInfo["user_id"]     = $userId;
        $arrInfo["file_size"]   = $infos_arr->FileSize->value;
        $arrInfo["create_time"] = time();
        $arrInfo["file_md5"]    = md5_file($oss_url);
        $arrInfo["file_sha1"]   = sha1_file($oss_url);
        $fileKey = $arrInfo["file_key"]    = $arrInfo["file_md5"] . md5($arrInfo["file_sha1"]);
        $arrInfo["filename"]    = $file_name;
        $arrInfo["file_path"]   = $file_path;
        $arrInfo["suffix"]      = $infos_arr->Format->value;
        
        $AssetModel = new AssetModel();
        $findAsset = $AssetModel->where('file_key', $fileKey)->find();


        if (empty($findAsset)) {

            $AssetModel->insert($arrInfo);
            //
            if($config['cloud_manger']!=1)
                echo $alioss->saveFileToLocal($file_path);exit;
        }

        $this->success('success');

    }

    private function gmt_iso8601($time) {
        $dtStr = date("c", $time);
        $mydatetime = new DateTime($dtStr);
        $expiration = $mydatetime->format(DateTime::ISO8601);
        $pos = strpos($expiration, '+');
        $expiration = substr($expiration, 0, $pos);
        return $expiration."Z";
    }

    

}

<?php
/*
 * @Author: Mr.snail 
 * @Date: 2020-07-13 15:12:23 
 * @Last Modified by: Mr.snail
 * @Last Modified time: 2020-07-13 15:12:46
 */
namespace plugins\alioss\lib;

use OSS\OssClient;
use OSS\Core\OssException;

class Alioss
{

    private $config;

    private $storageRoot;

    /**
     * @var \plugins\alioss\AliossPlugin
     */
    private $plugin;

    /**
     * Alioss constructor.
     * @param $config
     */
    public function __construct($config)
    {
        $pluginClass = cmf_get_plugin_class('Alioss');

        $this->plugin = new $pluginClass();
        $this->config = $this->plugin->getConfig();
        $this->config['style_separator'] = '?x-oss-process=image';
        $this->storageRoot = "{$this->config['protocol']}://".$this->getDomain()."/{$this->config['dir']}";
    }

    /**
     * 文件上传
     * @param string $file     上传文件路径
     * @param string $filePath 文件路径相对于upload目录
     * @param string $fileType 文件类型,image,video,audio,file
     * @param array  $param    额外参数
     * @return mixed
     */
    public function upload($file, $filePath, $fileType = 'image', $param = null)
    {
     
       
        $accessKeyId = $this->config['accessKey'];
        $accessKeySecret = $this->config['secretKey'];
        $endpoint = "{$this->config['protocol']}://{$this->config['region']}.aliyuncs.com";
        $bucket= $this->config['bucket'];
        $object = $this->config['dir'].$file;
        $bucketfile = $filePath;

        //$watermark = $this->config['styles_watermark'];
        $watermark = "";
        $options = array(
            OssClient::OSS_CHECK_MD5 => true,
            OssClient::OSS_PART_SIZE => 512*1024,
        );
        try{
            $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);

            $r = $ossClient->multiuploadFile($bucket, $object, $bucketfile, $options);

            $previewUrl = $fileType == 'image' ? $this->getPreviewUrl($file, $watermark) : $this->getFileDownloadUrl($file);
            $url        = $fileType == 'image' ? $this->getImageUrl($file, $watermark) : $this->getFileDownloadUrl($file);

            return [
                'preview_url' => $previewUrl,
                'url'         => $url,
            ];
        } catch(OssException $e) {
            printf(__FUNCTION__ . ": FAILED\n");
            printf($e->getMessage() . "\n");
            return;
        }
       
        
        
        
        
    }

    /**
     * 获取图片预览地址
     * @param string $file
     * @param string $style
     * @return mixed
     */
    public function getPreviewUrl($file, $style = 'watermark')
    {
        $url = $this->getUrl($file, $style);

        return $url;
    }

    /**
     * 获取图片地址
     * @param string $file
     * @param string $style
     * @return mixed
     */
    public function getImageUrl($file, $style = 'watermark')
    {
        $config = $this->config;
        $url    = $this->storageRoot . $file;
        $url = $this->setStyle($url,$style);

        return $url;
    }

    /**
     * 获取文件地址
     * @param string $file
     * @param string $style
     * @return mixed
     */
    public function getUrl($file, $style = '')
    {
        $config = $this->config;
        $url    = $this->storageRoot . $file;

        $url = $this->setStyle($url,$style);

        return $url;
    }

    /**
     * 获取文件下载地址
     * @param string $file
     * @param int    $expires
     * @return mixed
     */
    public function getFileDownloadUrl($file, $expires = 3600)
    {
        $url       = $this->getUrl($file);
        $filename  = db('asset')->where('file_path', $file)->value('filename');
        if (!empty($filename)) {
            $url .= '&attname=' . urlencode($filename);
        }
        return $url;
    }

    /**
     * 获取云存储域名
     * @return mixed
     */
    public function getDomain()
    {   
        if(!empty($this->config['domain']))
            return $this->config['domain'];
        else
            return "{$this->config['bucket']}.{$this->config['region']}.aliyuncs.com";
    }

    /**
     * 获取文件相对上传目录路径
     * @param string $url
     * @return mixed
     */
    public function getFilePath($url)
    {
        $parsedUrl = parse_url($url);
    
        if (!empty($parsedUrl['path'])) {
            $url            = ltrim($parsedUrl['path'], '/\\');
            $config         = $this->config;
            $styleSeparator = $config['style_separator'];

            $styleSeparatorPosition = strpos($url, $styleSeparator);
            if ($styleSeparatorPosition !== false) {
                $url = substr($url, 0, strpos($url, $styleSeparator));
            }
            $url            = ltrim($url, $this->config["dir"]);
        } else {
            $url = '';
        }
       
        return $url;
    }

    private function setStyle($url,$style=""){
        
        if (!empty($style)) {
            switch($style){
                case 'avatar':
                    $style = '/resize,m_fill,w_100,h_100';
                break;
                case 'watermark':
                    $style = '';
                    if(!empty($this->config['styles_watermark'])){
                        $style = "/watermark,{$this->config['styles_watermark']}";
                    }
                    if(!empty($this->config['text_watermark']) && !empty($this->config['styles_watermark'])){
                        $style .= ",text_".$this->urlsafe_b64encode($this->config['text_watermark']);
                    }
                break;
                case 'thumbnail120x120':
                    $style = '/resize,w_120,h_120';
                break;
                case 'thumbnail300x300':
                    $style = '/resize,w_300,h_300';
                break;
                case 'thumbnail640x640':
                    $style = '/resize,w_640,h_640';
                break;
                case 'thumbnail1080x1080':
                    $style = '/resize,w_1080,h_1080';
                break;
            }
            if(!empty($style))
                $url = $url . $this->config['style_separator'] . $style;
        }
        return $url;
    }

    public function saveFileToLocal($file){
        $accessKeyId = $this->config['accessKey'];
        $accessKeySecret = $this->config['secretKey'];
        $endpoint = "{$this->config['protocol']}://{$this->config['region']}.aliyuncs.com";
        $bucket= $this->config['bucket'];
        $object = $this->config['dir'].$file;
        $localfile = WEB_ROOT . 'upload/'.$file;
        $options = array(
                OssClient::OSS_FILE_DOWNLOAD => $localfile
            );

        // 使用try catch捕获异常，如果捕获到异常，则说明下载失败；如果没有捕获到异常，则说明下载成功。
        try{
           
            $this->creatdir(dirname($localfile));
            $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
            $ossClient->getObject($bucket, $object, $options);
        } catch(OssException $e) {
            return $e->getMessage();
        }
    }

    private function urlsafe_b64encode($string) {
        $data = base64_encode($string);
        $data = str_replace(array('+','/','='),array('-','_',''),$data);
        return $data;
    }

    private  function creatdir( $path ) {
        if ( !is_dir( $path ) ) {
            if ( $this->creatdir( dirname( $path ) ) ) {
                mkdir( $path, 0777 );
                return true;
            }
        } else {
            return true;
        }
    }
}
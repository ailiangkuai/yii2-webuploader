<?php
namespace ailiangkuai\yii2\widgets\webuploader\actions;

use ailiangkuai\yii2\widgets\webuploader\components\FileManager;
use Yii;
use yii\base\Action;
use yii\helpers\Json;
use yii\web\UnauthorizedHttpException;


/**
 * Class ImageBase64UploaderAction 用于post上传base64的图片文件
 * @package xsteach\yii\widgets\webuploader\actions
 * @author yaoyongfeng
 */
class ImageBase64UploaderAction extends Action
{
    public $enableCsrfValidation = false;

    public $imageAllowFiles = [".png", ".jpg", ".jpeg", ".gif", ".bmp"]; /* 上传图片格式显示 */
    public $imageMaxSize = 10240000; /* 上传大小限制，单位B */
    public $imageFieldName = 'upimg'; /* 提交的图片表单名称 */
    public $callback;
    public $isJson = false;
    public function __construct($id, $controller, $config = []) {
        parent::__construct($id, $controller, $config);
        $this->controller->enableCsrfValidation = $this->enableCsrfValidation;
    }


    public function run() {
        /* @var FileManager $uploader */
        $uploader = \Yii::$app->uploader;
        $uploader->setRules([
                'image' => [
                    'class'      => 'yii\validators\ImageValidator',
                    'extensions' => array_filter($this->imageAllowFiles, function ($value) {
                        trim($value, '.');
                    }
                    ),
                    'maxSize'    => $this->imageMaxSize,
                ]
            ]
        );
        $fileId = null;
        $status = 'SUCCESS';
        $size = 0;
        if(isset($_POST[$this->imageFieldName])){
            $imageContent = base64_decode($_POST[$this->imageFieldName]);
            /* @var FileManager $uploader */
            $tempFile = tempnam(sys_get_temp_dir(), 'avatar');
            $fp = fopen($tempFile, 'a');
            fwrite($fp, $imageContent);
            fclose($fp);
            $size = filesize($tempFile);
            $_FILES[$this->imageFieldName] = [
                'name'     => uniqid() . '.jpg',
                'type'     => 'image/jpeg',
                'tmp_name' => $tempFile,
                'error'    => 0,
                'size'     => $size
            ];

            try {
                $fileId = $uploader->upload($this->imageFieldName, 'image');
            } catch (\Exception $e) {
                $status = $e->getMessage();
            }
            if($this->callback){
                call_user_func($this->callback,$fileId);
            }
        }
        $result = [
            'status' => $status,
            'fileId' => $fileId,
            'ext'    => $status == 'SUCCESS' ? $uploader->getFileType(substr($fileId, -3)) : '',
            'url'    => $status == 'SUCCESS' ? $uploader->getFileUrl($fileId, true) : ''
        ];

        if($this->isJson){
            return Json::encode($result);
        } else {
            return $result;
        }



    }
}

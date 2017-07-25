<?php
namespace ailiangkuai\yii2\widgets\webuploader\actions;

use yii\base\Action;
use yii\helpers\Json;
use ailiangkuai\yii2\widgets\webuploader\components\FileManager;


/**
 * Class ImageUploaderAction
 * @package ailiangkuai\yii2\widgets\webuploader\actions
 * @author yaoyongfeng
 */
class ImageUploaderAction extends Action
{
    public $enableCsrfValidation = false;

    public $imageAllowFiles = [".png", ".jpg", ".jpeg", ".gif", ".bmp"]; /* 上传图片格式显示 */
    public $imageMaxSize = 10240000; /* 上传大小限制，单位B */
    public $imageFieldName = 'upimg'; /* 提交的图片表单名称 */
    public $isJson = true;
    public $callback;
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
        try {
            if (isset($_FILES[$this->imageFieldName])) {
                $fileId = $uploader->upload($this->imageFieldName, 'image');
            } else {
                $status = 'FILE INVALID!';
            }
        } catch (\Exception $e) {
            $status = $e->getMessage();
        }

        $result = [
            'status'  => $status,
            'fileId' => $fileId,
            'ext'    => $uploader->getFileType(substr($fileId, -3)),
            'url'    => $uploader->getFileUrl($fileId, true)
        ];
        if($this->callback){
            return call_user_func($this->callback, $result);
        }
        if($this->isJson){
            return Json::encode($result);
        } else {
            return $result;
        }
    }
}
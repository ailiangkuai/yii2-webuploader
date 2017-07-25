<?php
namespace ailiangkuai\yii2\widgets\webuploader\actions;

use yii\base\Action;
use yii\helpers\Json;
use ailiangkuai\yii2\widgets\webuploader\components\FileManager;


/**
 * Class AttachmentUploaderAction
 * @package xsteach\yii\widgets\webuploader\actions
 * @author yaoyongfeng
 */
class AttachmentUploaderAction extends Action
{
    public $enableCsrfValidation = false;

    public $attachmentAllowFiles = [".png", ".jpg", ".jpeg", ".gif", ".bmp", ".zip", ".rar", '.tar', '.gz', '.bz2', '.docx', '.doc', '.pdf', '.mp3', '.xls', '.xlsx', '.ppt', '.pptx', '.txt']; /* 上传图片格式显示 */
    public $attachmentMaxSize = 1024000000; /* 上传大小限制，单位B */
    public $attachmentFieldName = 'upatt'; /* 提交的图片表单名称 */
    public $uploadDir = '';
    public function __construct($id, $controller, $config = []) {
        parent::__construct($id, $controller, $config);
        $this->controller->enableCsrfValidation = $this->enableCsrfValidation;
    }


    public function run() {
        /* @var FileManager $uploader */
        $uploader = \Yii::$app->uploader;
        $uploader->setEnableChunk((bool)\Yii::$app->request->post('chunks'));
        $uploader->setTotalChunk(\Yii::$app->request->post('chunks'));
        $uploader->setCurrentChunk(\Yii::$app->request->post('chunk'));
        $uploader->setRules([
                'attachment' => [
                    'class'      => 'yii\validators\FileValidator',
                    'extensions' => array_filter($this->attachmentAllowFiles, function ($value) {
                        trim($value, '.');
                    }
                    ),
                    'maxSize'    => $this->attachmentMaxSize,
                ]
            ]
        );
        if($this->uploadDir) {
            $uploader->setUploadBaseDir($this->uploadDir);
        }
        $fileId = null;
        $status = 'SUCCESS';
        try {
            $fileId = $uploader->upload($this->attachmentFieldName, 'attachment');
        } catch (\Exception $e) {
            $status = $e->getMessage();
        }

        return Json::encode([
                'status'  => $status,
                'fileId' => $fileId,
                'ext'    => $uploader->getFileType(substr($fileId, -3)),
                'url'    => $uploader->getFileUrl($fileId, true)
            ]
        );
    }
}
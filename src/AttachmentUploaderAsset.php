<?php
namespace ailiangkuai\yii2\widgets\webuploader;

use yii\web\AssetBundle;
use Yii;

/**
 * Class AttachmentUploaderAsset
 * @package ailiangkuai\yii2\widgets\webuploader
 * @author yaoyongfeng
 */
class AttachmentUploaderAsset extends AssetBundle
{

    public $sourcePath = '@vendor/ailiangkuai/yii2-webuploader/src/assets';
    public $js = [
        'webuploader.js',
        'attachmentUploader.js',

    ];
    public $css = [
        'webuploader.css',
    ];


    public function init() {
    	$view = Yii::$app->getView();
    	$url = $view->assetManager->getPublishedUrl(Yii::getAlias($this->sourcePath));
    	$view->registerJs('WEBUPLOADER_HOME_URL="'.$url.'/";', $view::POS_HEAD, 'webuploader');
    
    	parent::init();
    }
}
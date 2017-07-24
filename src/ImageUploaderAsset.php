<?php
namespace ailiangkuai\yii2\webuploader;

use yii\web\AssetBundle;
use Yii;

class ImageUploaderAsset extends AssetBundle
{

    public $sourcePath = '@vendor/ailiangkuai/yii2-webuploader/src/assets';
    public $js = [
        'webuploader.js',
        'imageUploader.js',
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
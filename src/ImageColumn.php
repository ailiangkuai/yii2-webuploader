<?php
namespace ailiangkuai\yii2\widgets\webuploader;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use ailiangkuai\yii2\widgets\webuploader\components\FileManager;
use ailiangkuai\yii2\widgets\webuploader\components\BaseImage;


/**
 * Class ImageColumn
 * @package ailiangkuai\yii2\widgets\webuploader
 * @author yaoyongfeng
 */
class ImageColumn extends BaseColumn
{
    public $imgOptions = [];

    public function renderDataCell() {
        $tag     = ArrayHelper::remove($this->options, 'tag', 'li');
        $content = $this->createHidden() . Html::img($this->getImageUrl(), $this->imgOptions) . Html::tag('div', '', $this->helpOptions) . Html::a(ArrayHelper::remove($this->removeOptions, 'label', '删除'), 'javascript:;', $this->removeOptions);

        return Html::tag($tag, $content, $this->options);
    }

    protected function getImageUrl() {
        /* @var FileManager $uploader */
        $uploader = \Yii::$app->uploader;
        /* @var BaseImage $imageManager */
        $imageManager = \Yii::$app->imageManager;
        $file         = $uploader->getStorage()->getFile($this->value);
        if ($file) {
            $imageManager->setFile($file);
            $imageManager->setWidth(ArrayHelper::getValue($this->imgOptions, 'width', 110));
            $imageManager->setHeight(ArrayHelper::getValue($this->imgOptions, 'height', 110));
            $imageManager->setPrefer(2);

            return $imageManager->save();
        }
        
        //兼容数据库错误的fileId
        return (substr($this->value, 0, 7) == 'http://' || substr($this->value, 0, 8) == 'https://') ? $this->value : $uploader->getFileUrl($this->value);
    }
}
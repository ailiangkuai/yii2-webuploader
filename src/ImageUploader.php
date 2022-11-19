<?php
namespace ailiangkuai\yii2\widgets\webuploader;

use yii\helpers\ArrayHelper;
use yii\helpers\Json;


/**
 * Class ImageUploader
 * @package ailiangkuai\yii2\webuploader
 * @author yaoyongfeng
 */
class ImageUploader extends BaseUploader
{
    public $columnClass = '\ailiangkuai\yii2\widgets\webuploader\ImageColumn';

    function registerAssert(array $options) {
        $options['imgOptions']    = ArrayHelper::remove($options['options'], 'imgOptions', []);
        ImageUploaderAsset::register($this->getView());
        $this->getView()->registerJs("$('#{$this->options['id']}').ImageUploader(" . Json::encode($options) . ")");
    }

    public function initColumns(array $options) {
        $imgOptions                         = ArrayHelper::remove($options, 'imgOptions', []);
        $imgOptions['width']                = ArrayHelper::getValue($imgOptions, 'width', 110);
        $imgOptions['height']               = ArrayHelper::getValue($imgOptions, 'height', 110);
        $this->itemOptions['imgOptions']    = $imgOptions;
        if(ArrayHelper::getValue($this->clientOptions,'fileNumLimit',0)>1&&!empty($this->value)){
            $this->value = Json::decode($this->value);
        }
        foreach ($this->value as $index => $value) {
            $column              = \Yii::createObject([
                    'class' => $this->columnClass, 'imgOptions' => $imgOptions, 'removeOptions' => $this->itemOptions['removeOptions'], 'options' => $options, 'helpOptions' => $this->itemOptions['helpOptions'], 'uploader' => $this,
                    'value' => $value,
                ]
            );
            $this->value[$index] = $column;
        }
    }
}

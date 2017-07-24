<?php
namespace ailiangkuai\yii2\webuploader;

use yii\helpers\ArrayHelper;
use yii\helpers\Json;


/**
 * Class AttachmentUploader
 * @package ailiangkuai\yii2\webuploader
 * @author yaoyongfeng
 */
class AttachmentUploader extends BaseUploader
{
    public $columnClass = '\ailiangkuai\yii2\webuploader\AttachmentColumn';

    function registerAssert(array $options) {
        $options['contentOptions'] = ArrayHelper::remove($options['options'], 'contentOptions', []);
        AttachmentUploaderAsset::register($this->getView());
        $this->getView()->registerJs("$('#{$this->options['id']}').xstAttachmentUploader(" . Json::encode($options) . ")");
    }

    public function initColumns(array $options) {
        $contentOptions                      = ArrayHelper::remove($options, 'contentOptions', []);
        $contentOptions['class']             = ArrayHelper::getValue($contentOptions, 'class', 'uploader-name');
        $this->itemOptions['contentOptions'] = $contentOptions;
        $values                              = [];
        if (ArrayHelper::isIndexed($this->value)) {
            foreach ($this->value as $value) {
                $values[] = $this->createColumn($value, $options);
            }
        } else {
            $values[] = $this->createColumn($this->value, $options);
        }
        $this->value = $values;
    }

    protected function createColumn($value, $options) {
        return \Yii::createObject([
                'class' => $this->columnClass, 'contentOptions' => $this->itemOptions['contentOptions'], 'removeOptions' => $this->itemOptions['removeOptions'], 'options' => $options, 'helpOptions' => $this->itemOptions['helpOptions'], 'uploader' => $this,
                'value' => is_array($value) ? $value : Json::decode($value),
            ]
        );
    }
}
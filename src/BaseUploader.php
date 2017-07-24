<?php
namespace ailiangkuai\yii2\webuploader;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\InputWidget;


/**
 * Class BaseUploader
 * @package ailiangkuai\yii2\webuploader
 * @author yaoyongfeng
 */
abstract class BaseUploader extends InputWidget
{
    public $url;
    public $clientOptions = [];
    public $itemOptions = [];
    public $pickOptions = [];
    public $uploadOptions = [];
    private $_parts = [];
    public $template = "{pick}\n{upload}\n{items}";
    public $columnClass;
    /**
     * @var array
     */
    public $value;

    abstract public function registerAssert(array $options);

    abstract public function initColumns(array $options);

    public function init() {
        $this->pickOptions['id']   = ArrayHelper::getValue($this->pickOptions, 'id', 'uploader-' . ($this->hasModel() ? Html::getInputId($this->model, $this->attribute) : $this->name) . '-pick');
        $this->uploadOptions['id'] = ArrayHelper::getValue($this->uploadOptions, 'id', 'uploader-' . ($this->hasModel() ? Html::getInputId($this->model, $this->attribute) : $this->name) . '-upload');
        $this->options['id']       = ArrayHelper::getValue($this->options, 'id', 'uploader-' . ($this->hasModel() ? Html::getInputId($this->model, $this->attribute) : $this->name) . '-items');
        $this->options['class']    = ArrayHelper::getValue($this->options, 'class', 'uploader-items') ?: 'uploader-items';
        $this->initValues();
    }

    protected function initValues() {
        $this->value                        = ($this->hasModel() ? $this->model->getAttribute($this->attribute) : $this->value) ?: [];
        $this->value                        = is_array($this->value) ? $this->value : [$this->value];
        $options                            = $this->itemOptions;
        $removeOptions                      = ArrayHelper::remove($options, 'removeOptions', []);
        $cancelOptions                      = ArrayHelper::remove($options, 'cancelOptions', []);
        $helpOptions                        = ArrayHelper::remove($options, 'helpOptions', []);
        $cancelOptions['class']             = ArrayHelper::getValue($cancelOptions, 'class', 'uploader-cancel');
        $cancelOptions['label']             = ArrayHelper::getValue($cancelOptions, 'label', '取消');
        $removeOptions['class']             = ArrayHelper::getValue($removeOptions, 'class', 'uploader-remove');
        $removeOptions['label']             = ArrayHelper::getValue($removeOptions, 'label', '删除');
        $helpOptions['class']               = ArrayHelper::getValue($helpOptions, 'class', 'uploader-help');
        $this->itemOptions['removeOptions'] = $removeOptions;
        $this->itemOptions['cancelOptions'] = $cancelOptions;
        $this->itemOptions['helpOptions']   = $helpOptions;
        $this->initColumns($options);
    }

    public function run() {
        $this->renderButton();
        $this->renderItems();
        $this->registerClient();

        return $this->renderInput() . strtr($this->template, $this->_parts);
    }

    private function renderButton() {
        $pickLabel                   = ArrayHelper::remove($this->pickOptions, 'label', '选择文件');
        $this->_parts['{pick}']      = Html::a($pickLabel, 'javascript:;', $this->pickOptions);
        $this->_parts['{upload}']    = Html::a(ArrayHelper::remove($this->uploadOptions, 'label', '开始上传'), 'javascript:;', $this->uploadOptions);
        $this->clientOptions['pick'] = array_merge(ArrayHelper::getValue($this->clientOptions, 'pick', []), ['id' => '#' . $this->pickOptions['id'], 'innerHTML' => $pickLabel]);
    }

    private function renderItems() {
        $this->options['item']   = ArrayHelper::getValue($this->options, 'item', function ($item, $index) {
            /* @var BaseColumn $item */
            return $item->renderDataCell();
        }
        );
        $this->_parts['{items}'] = Html::ul($this->value, $this->options);
    }

    private function registerClient() {
        $options                        = $this->itemOptions;
        $clientOptions['clientOptions'] = $this->clientOptions;;
        $clientOptions['removeOptions'] = ArrayHelper::remove($options, 'removeOptions', []);
        $clientOptions['cancelOptions'] = ArrayHelper::remove($options, 'cancelOptions', []);
        $clientOptions['helpOptions']   = ArrayHelper::remove($options, 'helpOptions', []);
        $clientOptions['uploadOptions'] = $this->uploadOptions;
        $clientOptions['options']       = $options;
        $clientOptions['id']            = $this->options['id'];
        $clientOptions['name']          = $this->getInputName();
        $clientOptions['input']         = $this->getInputId();
        $this->registerAssert($clientOptions);
    }

    public function hasModel() {
        return parent::hasModel();
    }

    public function getInputName() {
        return $this->hasModel() ? Html::getInputName($this->model, $this->attribute . '[]') : $this->name . '[]';
    }

    public function getInputId() {
        return $this->hasModel() ? Html::getInputId($this->model, $this->attribute) : $this->name;
    }

    public function renderInput() {
        return $this->hasModel() ? Html::activeHiddenInput($this->model, $this->attribute, ['value' => (bool)$this->model->{$this->attribute}]) : Html::hiddenInput($this->name, (bool)$this->value);
    }
}
<?php
namespace ailiangkuai\yii2\webuploader;

use yii\base\Object;
use yii\helpers\Html;


/**
 * Class BaseColumn
 * @package ailiangkuai\yii2\webuploader
 * @author yaoyongfeng
 */
abstract class BaseColumn extends Object
{
    public $options = [];
    public $removeOptions = [];
    public $helpOptions = [];
    public $value;
    /**
     * @var BaseUploader
     */
    public $uploader;

    abstract public function renderDataCell();

    protected function createHidden() {
        return $this->uploader->hasModel() ?
            Html::activeHiddenInput($this->uploader->model, $this->uploader->attribute, ['value' => $this->value, 'name' => Html::getInputName($this->uploader->model, $this->uploader->attribute) . '[]'])
            : Html::hiddenInput($this->uploader->name . '[]', $this->value);
    }
}
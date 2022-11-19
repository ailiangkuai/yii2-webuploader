<?php
namespace ailiangkuai\yii2\widgets\webuploader;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;


/**
 * Class AttachmentColumn
 * @package ailiangkuai\yii2\widgets\webuploader
 * @author yaoyongfeng
 */
class AttachmentColumn extends BaseColumn
{
    public $contentOptions = [];

    public function renderDataCell() {
        $tag        = ArrayHelper::remove($this->options, 'tag', 'li');
        $name       = ArrayHelper::getValue($this->value, 'name', '');
        $size       = ArrayHelper::getValue($this->value, 'size', '');
        $ext        = ArrayHelper::getValue($this->value, 'ext', 'file');
        $contentTag = ArrayHelper::remove($this->contentOptions, 'tag', 'span');
        //$content    = $this->createHidden() . Html::img($this->getIcon($ext), ['title' => $name]) . Html::tag($contentTag, $name . ($size ? '(' . $this->converterSize($size) . ')' : ''), $this->contentOptions) . Html::tag('div', '', $this->helpOptions) . Html::a(ArrayHelper::remove($this->removeOptions, 'label', '删除'), 'javascript:;', $this->removeOptions);
        $content    = Html::img($this->getNewIcon($ext), array_merge(['title' => $name],['data-file'=>$this->value])) . Html::tag($contentTag, $name . ($size ? '(' . $this->converterSize($size) . ')' : ''), $this->contentOptions) . Html::tag('div', '', $this->helpOptions) . Html::a(ArrayHelper::remove($this->removeOptions, 'label', '删除'), 'javascript:;', $this->removeOptions);

        return Html::tag($tag, $content, $this->options);
    }

    protected function createHidden() {
        $oldValue    = $this->value;
        $this->value = Json::encode($this->value);
        $result      = parent::createHidden();
        $this->value = $oldValue;

        return $result;
    }

    protected function converterSize($size) {
        $K = 1000;
        $M = 1000000;
        $G = 1000000000;
        if ($size >= $M && $size < $G) {
            $value = number_format($size / $M, 2) . 'MB';
        } else {
            if ($size >= $G) {
                $value = number_format($size / $G, 2) . 'GB';
            } else {
                $value = number_format($size / $K, 2) . 'KB';
            }
        }

        return $value;
    }

    protected function getIcon($ext) {
        $items = [
            "file" => "default.png",
            "rar"  => "rar.png",
            "zip"  => "zip.png",
            "tar"  => "zip.png",
            "gz"   => "zip.png",
            "bz2"  => "zip.png",
            "doc"  => "doc.png",
            "docx" => "doc.png",
            "pdf"  => "pdf.png",
            "mp3"  => "mp3.png",
            "xls"  => "xls.png",
            "xlsx" => "xls.png",
            "ppt"  => "ppt.png",
            "pptx" => "ppt.png",
            "avi"  => "mp4.png",
            "rmvb" => "mp4.png",
            "wmv"  => "mp4.png",
            "flv"  => "mp4.png",
            "swf"  => "mp4.png",
            "rm"   => "mp4.png",
            "txt"  => "txt.png",
            "jpg"  => "jpg.png",
            "png"  => "png.png",
            "jpeg" => "jpg.png",
            "gif"  => "gif.png",
            "ico"  => "jpg.png",
            "bmp"  => "jpg.png"
        ];
        $view  = \Yii::$app->getView();
        $url   = $view->assetManager->getPublishedUrl((new AttachmentUploaderAsset())->sourcePath) . '/images/';

        return $url . ArrayHelper::getValue($items, $ext, 'default.png');
    }

	    protected function getNewIcon($ext) {
        $items = [
            "file" => "default.png",
            "rar"  => "zip.png",
            "zip"  => "zip.png",
            "tar"  => "zip.png",
            "gz"   => "zip.png",
            "bz2"  => "zip.png",
            "doc"  => "doc.png",
            "docx" => "doc.png",
            "pdf"  => "pdf.png",
            "mp3"  => "mp3.png",
            "xls"  => "xls.png",
            "xlsx" => "xls.png",
            "ppt"  => "ppt.png",
            "pptx" => "ppt.png",
            "avi"  => "avi.png",
            "rmvb" => "avi.png",
            "wmv"  => "avi.png",
            "flv"  => "avi.png",
            "swf"  => "avi.png",
            "rm"   => "avi.png",
            "txt"  => "txt.png",
            "jpg"  => "jpg.png",
            "png"  => "jpg.png",
            "jpeg" => "jpg.png",
            "gif"  => "jpg.png",
            "ico"  => "jpg.png",
            "bmp"  => "jpg.png"
        ];
        $view  = \Yii::$app->getView();
        $url   = $view->assetManager->getPublishedUrl((new AttachmentUploaderAsset())->sourcePath) . '/images/new_icons/';

        return $url . ArrayHelper::getValue($items, $ext, 'default.png');
    }
}

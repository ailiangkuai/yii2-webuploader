<?php
namespace ailiangkuai\yii2\webuploader\components;

use yii\helpers\ArrayHelper;
use Yii;
use yii\base\Object;
use yii\helpers\FileHelper;


/**
 * Class File
 * @package ailiangkuai\yii2\webuploader\components
 * @author yaoyongfeng
 */
class File extends Object
{
    protected $_basename;
    protected $_name;
    protected $_mime;
    protected $_extension;
    protected $_size;
    protected $_path;
    protected $_pathname;

    public function init() {
        $info = pathinfo($this->getPathname());
        $name = $this->getName() ?: ArrayHelper::getValue($info, 'basename');
        $this->_path = ArrayHelper::getValue($info, 'dirname');
        $this->_basename = $this->getName() ? substr($name, 0, strrpos($name, '.') ? strrpos($name, '.') : strlen($name)) :ArrayHelper::getValue($info, 'filename');
        $this->_mime = FileHelper::getMimeType($this->getPathname());
        $this->_extension = substr(strrchr($name, '.'), 1) ?: '';
        $this->_name = $name;
        $this->_size = filesize($this->getPathname());
    }

    /**
     * Basename
     * @return mixed
     */
    public function getBasename() {
        return $this->_basename;
    }

    /**
     * Name
     * @return mixed
     */
    public function getName() {
        return $this->_name;
    }

    /**
     * Mime
     * @return mixed
     */
    public function getMime() {
        return $this->_mime;
    }

    /**
     * Size
     * @return mixed
     */
    public function getSize() {
        return $this->_size;
    }

    /**
     * Path
     * @return mixed
     */
    public function getPath() {
        return $this->_path;
    }

    /**
     * Pathname
     * @return mixed
     */
    public function getPathname() {
        return $this->_pathname;
    }

    /**
     * @param mixed $pathname
     *
     */
    public function setPathname($pathname) {
        $this->_pathname = $pathname;
    }

    /**
     * Extension
     * @return mixed
     */
    public function getExtension() {
        return $this->_extension;
    }

    /**
     * @param mixed $name
     *
     */
    public function setName($name) {
        $this->_name = $name;
    }


}
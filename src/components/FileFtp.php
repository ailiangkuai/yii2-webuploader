<?php
namespace ailiangkuai\yii2\widgets\webuploader\components;


/**
 * Class FileFtp
 * @package ailiangkuai\yii2\widgets\webuploader\components
 * @author yaoyongfeng
 */
class FileFtp extends File
{
    private $_metaData;

    /**
     * @return mixed
     */
    public function getMetaData()
    {
        return $this->_metaData;
    }

    /**
     * @param mixed $metaData
     */
    public function setMetaData($metaData)
    {
        $this->_metaData = $metaData;
    }
    public function init()
    {
        $filename = substr(strrchr($this->getPathname(), DIRECTORY_SEPARATOR), 1);
        $this->_path =substr($this->getPathname(), 0, strrpos($this->getPathname(), DIRECTORY_SEPARATOR));
        $this->_basename =substr($filename, 0, strrpos($filename, '.') ? strrpos($filename, '.') : strlen($filename));
        $this->_extension = substr(strrchr($filename, '.'), 1) ?: '';
        $this->_name=substr(strrchr($this->getPathname(), DIRECTORY_SEPARATOR), 1);
        $this->_size = $this->_metaData['size'] ?: 0;
    }

}
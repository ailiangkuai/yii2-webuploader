<?php
namespace ailiangkuai\yii2\webuploader\components;

use yii\base\Object;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;


/**
 * Class FileManager
 * @package ailiangkuai\yii2\webuploader\components
 * @author yaoyongfeng
 */
class FileManager extends Object
{
    private $_rules;
    private $_enableChunk = false;
    private $_totalChunk;
    private $_currentChunk;

    /**
     * @var BaseStorage
     */
    private $_storage;

    /**
     * @param $_basePath
     *
     * @deprecated 请使用 [[self::setBasePath]]
     * @since 1.0
     */
    public function setUploadBasePath($_basePath) {
        $this->getStorage()->setBasePath($_basePath);
    }

    public function getUploadBasePath() {
        return $this->getStorage()->getBasePath();
    }

    public function setUploadBaseDir($_baseDir) {
        $this->getStorage()->setBaseDir($_baseDir);
    }

    /**
     * @param array $storage
     *
     * @since 1.0
     * @throws \yii\base\InvalidConfigException
     */
    public function setStorage(array $storage) {
        $this->_storage = \Yii::createObject($storage);
    }

    /**
     * @since 1.0
     * @return BaseStorage
     */
    public function getStorage() {
        return $this->_storage;
    }

    public function setRules(array $rule) {
        $this->_rules = $rule;
    }

    /**
     * EnableChunk
     * @return boolean
     */
    public function isEnableChunk() {
        return $this->_enableChunk;
    }

    /**
     * @param boolean $enableChunk
     *
     */
    public function setEnableChunk($enableChunk) {
        $this->_enableChunk = $enableChunk;
    }

    /**
     * TotalChunk
     * @return mixed
     */
    public function getTotalChunk() {
        return $this->_totalChunk;
    }

    /**
     * @param mixed $totalChunk
     *
     */
    public function setTotalChunk($totalChunk) {
        $this->_totalChunk = $totalChunk;
    }

    /**
     * CurrentChunk
     * @return mixed
     */
    public function getCurrentChunk() {
        return $this->_currentChunk;
    }

    /**
     * @param mixed $currentChunk
     *
     */
    public function setCurrentChunk($currentChunk) {
        $this->_currentChunk = $currentChunk;
    }

    public function getTempPath() {
        $result = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $this->getStorage()->getBaseDir() . DIRECTORY_SEPARATOR;
        !is_dir($result) && @mkdir($result, 0777, true);

        return $result;
    }

    public function setTempPath($tempPath) {

    }

    public function getFileUrl($hashFile, $scheme = false) {
        return $this->getStorage()->getFileUrl($hashFile);
    }

    public function getFilePath($hasFile) {
        return $this->getStorage()->getFilePath($hasFile);
    }

    /**
     * @param $typeId
     *
     * @deprecated
     * @since 1.0
     * @return string
     */
    public function getFileType($typeId) {
        return $this->getStorage()->getFileExt($typeId);
    }

    public function upload($field = 'image', $type = 'image') {
        $uploadedFile = UploadedFile::getInstanceByName($field);
        if (!$this->save($uploadedFile)) {
            return false;
        }
        if (null == $uploadedFile) {
            throw new FileNotFoundException('没有上传文件');
        }
        if (array_key_exists($type, $this->_rules)) {
            $error     = '';
            $validator = \Yii::createObject($this->_rules[$type]);
            if (!$validator->validate($uploadedFile, $error)) {
                throw new \Exception($error);
            }
        }

        return $this->getStorage()->save(new File(['pathname' => $uploadedFile->tempName, 'name' => $uploadedFile->name]), true);
    }

    private function save(UploadedFile $uploadedFile) {
        if ($this->isEnableChunk()) {
            $uploadedFile->saveAs($this->getTempPath() . $uploadedFile->name . $this->getCurrentChunk() . '.part');

            return $this->isCompleteChunk($uploadedFile) && $this->mergeChunk($uploadedFile);
        }

        return true;
    }

    private function isCompleteChunk(UploadedFile $uploadedFile) {
        $result = true;
        for ($i = 0; $i < $this->getTotalChunk(); $i++) {
            if (!file_exists($this->getTempPath() . $uploadedFile->name . $i . '.part')) {
                $result = false;
                break;
            }
        }

        return $result;
    }

    private function mergeChunk(UploadedFile $uploadedFile) {
        if (($file = @fopen($this->getTempPath() . $uploadedFile->name, 'wb')) && flock($file, LOCK_EX)) {
            for ($i = 0; $i < $this->getTotalChunk(); $i++) {
                if (!$in = @fopen($this->getTempPath() . $uploadedFile->name . $i . '.part', 'rb')) {
                    break;
                }
                while ($buff = fread($in, 4096)) {
                    fwrite($file, $buff);
                }
                @fclose($in);
                @unlink($this->getTempPath() . $uploadedFile->name . $i . '.part');
            }
            flock($file, LOCK_UN);
            @fclose($file);
            $uploadedFile->tempName = $this->getTempPath() . $uploadedFile->name;

            return true;
        }

        return false;
    }
}
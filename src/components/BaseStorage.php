<?php
namespace ailiangkuai\yii2\webuploader\components;

use yii\base\Object;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;


/**
 * Class BaseStorage
 * @package ailiangkuai\yii2\webuploader\components
 * @author yaoyongfeng
 */
abstract class BaseStorage extends Object
{
    private $_baseUrl;
    private $_basePath;
    private $_baseDir;

    abstract public function save(File $source, $isDelete = false);

    abstract public function getFilePath($fileHash);

    abstract public function getFileUrl($fileHash);

    /**
     * File
     *
     * @param string $fileHash
     *
     * @since 1.0
     * @return File|null
     */
    abstract public function getFile($fileHash);

    protected static $_fileTypeMaps = [
        '000' => '',
        '001' => 'jpg',
        '002' => 'png',
        '003' => 'gif',
        '004' => 'swf',
        '005' => 'pdf',
        '006' => 'zip',
        '007' => 'rar',
        '008' => '7z',
        '009' => 'tar',
        '010' => 'doc',
        '011' => 'docx',
        '012' => 'ppt',
        '013' => 'pptx',
        '014' => 'xls',
        '015' => 'xlsx',
        '016' => 'bmp',
        '017' => 'psd',
        '018' => 'mp3',
        '019' => 'mp4',
        '020' => 'wav',
        '021' => 'ape',
        '022' => 'aac',
        '023' => 'm4a',
        '024' => 'mpeg',
        '025' => 'wma',
        '026' => 'ogg',
        '027' => '3gp',
        '028' => 'tiff',
        '029' => 'cdr',
        '030' => 'fla',
        '031' => 'ai',
        '032' => 'txt',
        '033' => 'csv',
        '034' => 'apk'
    ];

    /**
     * 构造一个路径
     *
     * @param $fileHash
     * @param bool $isCreate
     *
     * @since 1.0
     * @return string
     */
    public function buildFilePath($fileHash, $isCreate = false) {
        $split = str_split($fileHash, 2);
        $path = (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? '' : DIRECTORY_SEPARATOR) . $this->getBasePath() . DIRECTORY_SEPARATOR . $this->getBaseDir() . DIRECTORY_SEPARATOR . $split[0] . DIRECTORY_SEPARATOR . $split[1] . DIRECTORY_SEPARATOR . $split[2] . DIRECTORY_SEPARATOR;
        if ($isCreate) {
            @mkdir($path, 0777, true);
        }

        return $path;
    }

    public function buildFileUrl($fileHash) {
        $split = str_split($fileHash, 2);
        return '/' . $this->getBaseDir() . '/' . $split[0] . '/' . $split[1] . '/' . $split[2] . '/';
    }

    /**
     * 根据扩展ID获取文件扩展名
     *
     * @param string $typeId	文件类型ID
     * @return string
     */
    public function getFileExt($typeId) {
        return ArrayHelper::getValue(static::$_fileTypeMaps, $typeId, '');
    }

    public function getFileExtId($ext) {
        return array_search($this->normalize($ext), static::$_fileTypeMaps) ?: '000';
    }

    public function hashFile(File $file) {
        return md5_file($file->getPathname()).$this->getFileExtId($file->getExtension());
    }

    public function normalize($ext) {
        $ext = strtolower($ext);
        switch ($ext) {
            case 'jpeg':
                return 'jpg';
        }

        return $ext;
    }

    /**
     * BaseUrl
     * @return mixed
     */
    public function getBaseUrl() {
        return $this->_baseUrl;
    }

    /**
     * @param mixed $baseUrl
     *
     */
    public function setBaseUrl($baseUrl) {
        $this->_baseUrl = $baseUrl;
    }

    /**
     * BasePath
     * @return mixed
     */
    public function getBasePath() {
        return $this->_basePath;
    }

    /**
     * @param mixed $basePath
     *
     */
    public function setBasePath($basePath) {
        $this->_basePath = $basePath;
    }

    /**
     * BaseDir
     * @return mixed
     */
    public function getBaseDir() {
        return $this->_baseDir;
    }

    /**
     * @param mixed $baseDir
     *
     */
    public function setBaseDir($baseDir) {
        $this->_baseDir = $baseDir;
    }
}
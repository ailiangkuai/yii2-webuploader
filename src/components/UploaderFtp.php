<?php
/**
 * 亿牛集团
 * 本源代码由亿牛集团及其作者共同所有，未经版权持有者的事先书面授权，
 * 不得使用、复制、修改、合并、发布、分发和/或销售本源代码的副本。
 *
 * @copyright Copyright (c) 2017 yiniu.com all rights reserved.
 */


namespace ailiangkuai\yii2\widgets\webuploader\components;


use yii\base\Exception;

class UploaderFtp extends BaseStorage
{
    private $_ftpClient;

    /**
     * @return \creocoder\flysystem\Filesystem
     */
    public function getFtpClient()
    {
        if (!$this->_ftpClient instanceof \creocoder\flysystem\Filesystem) {
            throw new Exception('ftpClient未设置');
        }
        return $this->_ftpClient;
    }

    /**
     * @param array|\creocoder\flysystem\Filesystem $ftpClient
     */
    public function setFtpClient($ftpClient)
    {
        $this->_ftpClient = $ftpClient instanceof \creocoder\flysystem\Filesystem ? $ftpClient : \Yii::createObject($ftpClient);
    }

    public function save(File $source, $isDelete = true)
    {
        $fileId = $this->hashFile($source);
        $ext = $this->getFileExt(substr($fileId, -3));
        $filePath = $this->buildFileUrl($fileId, true) . $fileId . ($ext ? ".{$ext}" : '');
        if ($this->saveFile($source, $filePath, true)) {
            if ($isDelete) {
                @unlink($source->getPathname());
            }
            return $fileId;
        }

        return false;
    }

    public function getFilePath($fileHash)
    {
        if (!$fileHash || strlen($fileHash) != 35) {
            return null;
        }
        $ext = $this->getFileExt(substr($fileHash, -3));

        return $this->buildFilePath($fileHash) . $fileHash . ($ext ? ".{$ext}" : '');
    }

    public function getFileUrl($fileHash)
    {
        if (!$fileHash || strlen($fileHash) != 35) {
            return null;
        }
        $ext = $this->getFileExt(substr($fileHash, -3));

        return $this->getBaseUrl() . $this->buildFileUrl($fileHash) . $fileHash . ($ext ? ".{$ext}" : '');
    }

    /**
     * File
     * @since 1.0
     * @return FileFtp|null
     */
    public function getFile($fileHash)
    {
        if ($filePath = $this->getFilePath($fileHash)) {
            return new FileFtp(['pathname' => $filePath]);
        } else {
            return null;
        }
    }


    /**
     * 保存到远端ftp
     * @param File $uploadedFile 临时文件
     * @param string $filePath 文件全路径
     * @return string|boolean
     */
    public function saveFile(File $uploadedFile, $filePath, $isDelete = true)
    {
        if (!file_exists($uploadedFile->getPathname())) {
            return false;
        }
        if ($this->getFtpClient()->has($filePath)) {
            $isDelete && @unlink($uploadedFile->getPathname());
            return true;
        }
        $fileStream = fopen($uploadedFile->getPathname(), 'rb');
        $result = $this->getFtpClient()->writeStream($filePath, $fileStream);
        fclose($fileStream);
        $isDelete && @unlink($uploadedFile->getPathname());
        return $result;
    }
}
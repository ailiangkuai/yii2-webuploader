<?php
namespace ailiangkuai\yii2\widgets\webuploader\components;

use Yii;

class Uploader extends BaseStorage
{
    public function save(File $source, $isDelete = false) {
        $fileId = $this->hashFile($source);
        $ext = $this->getFileExt(substr($fileId, -3));
        $filePath = $this->buildFilePath($fileId, true). $fileId . ($ext ? ".{$ext}" : '');
        if ($this->saveFile($source, $filePath, true)) {
            if ($isDelete) {
                @unlink($source->getPathname());
            }
            return $fileId;
        }

        return false;
    }

    public function getFilePath($fileHash) {
        if (!$fileHash || strlen($fileHash) != 35) {
            return null;
        }
        $ext = $this->getFileExt(substr($fileHash, -3));

        return $this->buildFilePath($fileHash) . $fileHash . ($ext ? ".{$ext}" : '');
    }

    public function getFileUrl($fileHash) {
        if (!$fileHash || strlen($fileHash) != 35) {
            return null;
        }
        $ext = $this->getFileExt(substr($fileHash, -3));

        return $this->getBaseUrl() . $this->buildFileUrl($fileHash) . $fileHash . ($ext ? ".{$ext}" : '');
    }

    /**
     * File
     * @since 1.0
     * @return File|null
     */
    public function getFile($fileHash) {
        if (file_exists($this->getFilePath($fileHash))) {
            return new File(['pathname' => $this->getFilePath($fileHash)]);
        } else {
            return null;
        }
    }


    /**
	 * 保存文件
	 *
	 * @param string $file
	 * @return string|boolean
	 */
	public function saveFile(File $uploadedFile, $file, $isDelete = false) {
        return file_exists($file) || (($isDelete && rename($uploadedFile->getPathname(), $file)) || copy($uploadedFile->getPathname(), $file));
	}
}
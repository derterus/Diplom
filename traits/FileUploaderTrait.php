<?php

namespace app\traits;

use Yii;
use yii\web\UploadedFile;
use app\models\UploadForm;

trait FileUploaderTrait
{
    public function uploadFile(UploadedFile $file, $uploadPath = 'uploads/')
    {
        $uploadModel = new UploadForm();
        $uploadModel->file = $file;
    
        // Логирование входных данных
        Yii::debug('Attempting to upload file: ' . $file->name, 'file-upload');
    
        if ($uploadModel->validate()) {
            $filePath = $uploadPath . uniqid() . '.' . $uploadModel->file->extension;
            // Логирование успешной валидации
            Yii::debug('File validated successfully. Saving file to: ' . $filePath, 'file-upload');
    
            if ($uploadModel->file->saveAs($filePath)) {
                // Логируем успешное сохранение
                Yii::debug('File uploaded successfully: ' . $filePath, 'file-upload');
                return $filePath;
            } else {
                Yii::error('Failed to save uploaded file: ' . serialize($uploadModel->getErrors()), 'file-upload');
                return false;
            }
        } else {
            Yii::error('File validation failed: ' . serialize($uploadModel->getErrors()), 'file-upload');
            return false;
        }
    }
    
}
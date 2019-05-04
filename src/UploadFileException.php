<?php


namespace Instantech;


use Exception;
use const UPLOAD_ERR_CANT_WRITE;
use const UPLOAD_ERR_EXTENSION;
use const UPLOAD_ERR_FORM_SIZE;
use const UPLOAD_ERR_INI_SIZE;
use const UPLOAD_ERR_NO_FILE;
use const UPLOAD_ERR_NO_TMP_DIR;
use const UPLOAD_ERR_PARTIAL;

class UploadFileException extends Exception implements UploadFileMessage
{

    /**
     * UploadFileException constructor.
     * @param $code
     */
    public function __construct($code) {
        $message = $this->getUploadFileErrorMessage($code);
        parent::__construct($message, $code);
    }


    /**
     * Renvoie un message en fonction de l'erreur
     * @param int $code
     * @return mixed
     */
    public function getUploadFileErrorMessage(int $code): string
    {
        {
            switch ($code) {
                case UPLOAD_ERR_INI_SIZE:
                    $message = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
                    break;
                case UPLOAD_ERR_FORM_SIZE:
                    $message = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
                    break;
                case UPLOAD_ERR_PARTIAL:
                    $message = 'The uploaded file was only partially uploaded';
                    break;
                case UPLOAD_ERR_NO_FILE:
                    $message = 'No file was uploaded';
                    break;
                case UPLOAD_ERR_NO_TMP_DIR:
                    $message = 'Missing a temporary folder';
                    break;
                case UPLOAD_ERR_CANT_WRITE:
                    $message = 'Failed to write file to disk';
                    break;
                case UPLOAD_ERR_EXTENSION:
                    $message = 'File upload stopped by extension';
                    break;
                default:
                    $message = 'Unknown upload error';
                    break;
            }
            return $message;
        }
    }
}
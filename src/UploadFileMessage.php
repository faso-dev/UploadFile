<?php


namespace Instantech;

interface UploadFileMessage
{
    /**
     * Renvoie un message en fonction de l'erreur
     * @param int $code
     * @return mixed
     */
    public function getUploadFileErrorMessage(int $code) : string ;
}
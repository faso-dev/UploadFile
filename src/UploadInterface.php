<?php


namespace Instantech;


interface UploadInterface
{
    /**
     * @return int
     */
    public function getFileSize(): int;

    /**
     * @return string
     */
    public function getFileName(): string;

    /**
     * @return string | null
     */
    public function getFileExtension() : ?string;

    /**
     * @return string
     */
    public function getFileTmpName(): string;

    /**
     * @return array
     */
    public function getFilePathInfo(): array ;

    /**
     * @return string
     */
    public function getFileType(): string;

    /**
     * @return int
     */
    public function getFileError(): int;

    /**
     * @return void
     */
    public function moveTo() : void ;

    /**
     * Supprime un fichier et renvoie true si tout s'est bien passé, et false sinon
     * @param $files
     * @return bool
     */
    public function removeFile($files) : bool ;

    /**
     * Renvoie true si aucune erreur
     * @return bool
     */
    public function isValid() : bool ;

}
<?php


namespace Instantech;


use function basename;
use function file_exists;
use function implode;
use function in_array;
use function move_uploaded_file;
use function pathinfo;
use function time;
use function uniqid;
use function unlink;

class UploadFile implements UploadInterface
{
    /**
     * @var array
     */
    private $files;

    /**
     * @var string key of file
     */
    private $key;
    /**
     * An array of extenxion that you want to authorize to upload
     * @var array
     */
    private $authorizeExtension;


    /**
     * @var int
     */
    private $authorizeSize;

    /**
     * @var string
     */
    private $destination;

    /**
     * UploadImg constructor.
     * @param array $files
     * @param $key
     * @param array|null $authorizeExtension
     */
    public function __construct(array $files, $key, array $authorizeExtension = [])
    {
        $this->files = $files;
        $this->key = $key;
        $this->authorizeExtension = $authorizeExtension;
    }

    /**
     * Renvoie la taille du fichier
     * @return int
     */
    public function getFileSize(): int
    {
        return $this->files[$this->getKey()]['size'];
    }

    /**
     * Renvoie le nom réel du fichier
     * @return string
     */
    public function getFileName(): string
    {
        return $this->files[$this->getKey()]['name'];
    }

    /**
     * Renvoie l'extenxion du fichier
     * @return string|null
     */
    public function getFileExtension() : ?string
    {
        return $this->getFilePathInfo()['extension'] ?? null;
    }

    /**
     * Renvoie le nom temporaire du fichier
     * @return string
     */
    public function getFileTmpName(): string
    {
        return $this->files[$this->getKey()]['tmp_name'];
    }

    /**
     * Renvoie l'ensemble des informations du fichier
     * @return array
     */
    public function getFilePathInfo(): array
    {
        return pathinfo($this->files[$this->getKey()]['name']);
    }

    /**
     * Renvoie le type du fichier
     * @return string
     */
    public function getFileType(): string
    {
        return $this->files[$this->getKey()]['type'];
    }

    /**
     * Renvoie le code d'erreur lors de l'envoie vers le serveur
     * @return int
     */
    public function getFileError(): int
    {
        return $this->files[$this->getKey()]['error'];
    }

    /**
     * Transfert le fichier vers le serveur s'il n'y pas d'erreur
     * @return void
     * @throws UploadFileException
     */
    public function moveTo(): void
    {
        if ($this->FileIsValid()) {
            $unique_name = uniqid(time(), true) . '.' . $this->getFileExtension();
            move_uploaded_file($this->getFileTmpName(), $this->getDestination() . '/' . basename($unique_name));

        } else {
            throw new UploadFileException($this->getFileError());
        }
    }


    /**
     * @return mixed
     */
    private function getKey()
    {
        return $this->key;
    }

    /**
     * Prend en paramètre le nom de la variable au niveau de votre formulaire
     * @param mixed $key
     * @return UploadFile
     */
    public function setKey($key): UploadFile
    {
        $this->key = $key;
        return $this;
    }

    /**
     * Renvoie la liste de toutes les extensions autorisées
     * @return array
     */
    public function getAuthorizeExtension() : array
    {
        return $this->authorizeExtension;
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        return isset($this->authorizeExtension)?implode(',',$this->authorizeExtension) : '';
    }

    /**
     * Permet de définir la liste des extension de fichier autorisée
     * @param mixed $authorizeExtension
     * @return UploadFile
     */
    public function setAuthorizeExtension($authorizeExtension): UploadFile
    {
        $this->authorizeExtension = $authorizeExtension;
        return $this;
    }

    /**
     * Renvoie la taille autorisée pour les fichier
     * @return int
     */
    public function getAuthorizeSize(): int
    {
        return $this->authorizeSize / 1000000;
    }

    /**
     * Permt de définir la taille autorisée pour les fichiers
     * @param $authorizeSize
     * @return UploadFile
     */
    public function setAuthorizeSize(int $authorizeSize): UploadFile
    {
        $this->authorizeSize = $authorizeSize;
        return $this;
    }

    /**
     * Renvoie vrai si le fichier existe, faux sinon
     * @return bool
     */
    public function FileExist(): bool
    {
        return isset($this->files[$this->getKey()]) && !empty($this->files[$this->getKey()]);
    }

    /**
     * Renvoie vrai si l'extension du fichier est valide
     * @return bool
     */
    public function isFileExtensionValide(): bool
    {
        return in_array($this->getFileExtension(), $this->authorizeExtension, true);
    }

    /**
     * Renvoie vraie si la taille du fichier est valide, sinon faux
     * @return bool
     */
    public function isFileSizeValid(): bool
    {
        return ($this->getFileSize() / 1000000) <= $this->getAuthorizeSize();
    }

    /**
     * Renvoie vrai si aucune erreur de validation du fichier n'est trouvée
     * Cette fonction ne s'occupe pas de vérifier les extensions et la taille
     * @return bool
     */
    public function FileIsValid(): bool
    {
        return $this->getFileError() === 0;
    }

    /**
     * Renvoie le chemin vers le dossier dans lequel placer les fichiers
     * @return string
     */
    private function getDestination(): string
    {
        return $this->destination;
    }

    /**
     * Définie le chemin de destination vers le dossier dans lequel placer les fichiers
     * @param string $destination
     * @return UploadFile
     */
    public function setDestination(string $destination): UploadFile
    {
        $this->destination = $destination;
        return $this;
    }

    /**
     * Supprime un fichier s'il existe, renvoie true si la supprésion à marché, faux sinon
     * @param $file
     * @return bool
     */
    public function removeFile($file): bool
    {
        if (file_exists($this->getDestination().'/'.$file)){
            return unlink($this->getDestination().'/'.$file);
        }
        return false;
    }

    /**
     * Renvoie true si tout est ok, false sinon
     * @return bool
     */
    public function isValid() : bool
    {
        return  $this->FileExist() &&
                $this->FileIsValid() &&
                $this->isFileSizeValid() &&
                $this->isFileExtensionValide();
    }

}
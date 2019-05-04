<pre>


use Instantech\UploadFile;
use Instantech\UploadFileException;

require_once __DIR__ . '../../vendor/autoload.php';
const PATH = '../images';
if (isset($_POST['send'])) {
    //Création d'un upload manager
    $FileUploadmanager = new UploadFile($_FILES, 'image', ['png', 'jpg', 'jpeg', 'gif', 'pdf']);

    // On définit la taille autorisé pour le fichier
    $FileUploadmanager->setAuthorizeSize(5000000)
        // On défint le chemin vers lequel sera enregistrer les fichiers
        ->setDestination(PATH);

    // Une autre façon plus claire pour capturer les différentes erreur
    if ($FileUploadmanager->FileExist()) {
        // On vérifie si le si l'extension du fichier est propre
        if ($FileUploadmanager->isFileExtensionValide()) {
            // On vérifie si la taille du fichier est correcte
            if ($FileUploadmanager->isFileSizeValid()) {
                // On éssaie d'envoyer le fichier sur le serveur
                try {
                    // Si tout s'est bien déroulé, on enregistre le fichier
                    $FileUploadmanager->moveTo();
                    $FileUploadmanager->removeFile('15570019805ccdf6fc49b192.31778794.png');
                } catch (UploadFileException $e) {
                    // En cas d'erreur, on affiche le message
                    echo $e->getMessage();
                }
            } else {
                // Si la taille du fichier est invalide, on affiche un message d'erreur
                $error = 'La taille du fichier doit être inférieur ou égale à ' . $FileUploadmanager->getFileSize() / 1000000;
            }
        } else {
            // Si l'extension du fichier est invalide, on affiche un message d'erreur
            $error = 'Le fichier doit être un fichier ' . $FileUploadmanager;
        }
    } else {
        $error = 'Vous devez soumettre un fichier';
    }

    //Ou si vous preferez la méthode la plus courte, il vous suffit de procéder comme suite
    if ($FileUploadmanager->isValid()) {
        try {
            $FileUploadmanager->moveTo();
            // Si vous voulez le nom du fichier pour l'inserer dans une base de donnée, c'est simple
            $fileName = $FileUploadmanager->getMovedFileName();
        } catch (UploadFileException $e) {
            $error = $e->getMessage();
        }
    } else {
        $error = sprintf('Veuillez vérifier le format, la taille, et le type de votre fichier : 
        extension autorisée ( %s ), taille à ne pas dépasser %d MO',
            $FileUploadmanager, $FileUploadmanager->getAuthorizeSize());
    }

}

</pre>


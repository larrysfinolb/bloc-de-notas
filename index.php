<?php
$isSaved = false;
$isLoaded = false;
$isDeleted = false;
$notFound = false;

$file = '';
$dir = './files/';

// Si se ha solicitado la eliminación de un archivo
if (isset($_GET["delete"]) && !empty($_GET['delete'])) {
    $filename = $_GET['delete'];
    if (file_exists($dir . $filename)) {
        unlink($dir . $filename);
        $isDeleted = true;
    }
}

// Si se ha solicitado la carga de un archivo
if (isset($_GET["file"]) && !empty($_GET['file'])) {
    $file = $_GET['file'];
    if (is_file($dir . $file)) {
        $isLoaded = true;
    } else {
        $notFound = true;
    }
}

// Si se ha enviado el formulario de guardado
if (isset($_POST["save"]) && isset($_POST['content']) && isset($_POST['filename']) && !empty($_POST['filename'])) {
    $filename = $_POST['filename'];
    if (strpos($filename, ".txt") === false) {
        $filename .= ".txt";
    }
    $content = $_POST['content'];
    $file_handle = fopen($dir . $filename, 'w');
    fwrite($file_handle, $content);
    fclose($file_handle);
    $isSaved = true;
}

// Se ha solicitado el listado de los archivos en el directorio
$files = scandir($dir);
$files = array_diff($files, array('.', '..'));
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bloc de notas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
</head>

<body>
    <div class="container-fluid min-vh-100 d-flex flex-column pt-3 pb-3" style="gap: 1rem">
        <?php if ($isSaved) { ?>
            <div class="alert alert-success m-0" role="alert">
                ¡El archivo a sido guardado!
            </div>
        <?php } else if ($isLoaded) { ?>
            <div class="alert alert-success m-0" role="alert">
                ¡El archivo a sido cargado!
            </div>
        <?php } else if ($isDeleted) { ?>
            <div class="alert alert-danger m-0" role="alert">
                ¡El archivo a sido eliminado!
            </div>
        <?php } else if ($notFound) { ?>
            <div class="alert alert-danger m-0" role="alert">
                ¡El archivo no existe!
            </div>
        <?php } ?>

        <!-- Titulo -->
        <h1 class="m-0 display-3 text-center text-uppercase fw-bold">Bloc de notas</h1>

        <!-- Botones de opciones -->
        <div class="d-flex g-3" style="gap: 1rem">
            <a href="./index.php" class="flex-grow-1 btn btn-primary"> Nuevo archivo</a>
            <button type="button" class="flex-grow-1 btn btn-primary" data-bs-toggle="modal" data-bs-target="#filesModal">
                Abrir archivo
            </button>
            <?php if (!$isLoaded) { ?>
                <button type="button" class="flex-grow-1 btn btn-primary" data-bs-toggle="modal" data-bs-target="#saveModal">
                    Guardar archivo
                </button>
            <?php } ?>
            <button type="button" class="flex-grow-1 btn btn-primary" data-bs-toggle="modal" data-bs-target="#searchModal">
                Buscar archivo
            </button>
        </div>

        <!-- Modal para abrir un archivo -->
        <div class="modal fade" id="filesModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="filesModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="filesModalLabel">Archivos</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <ul class="d-flex flex-column p-0" style="gap: 1rem">
                            <?php foreach ($files as $file_item) { ?>
                                <li class="d-flex justify-content-between align-items-center">
                                    <a href="index.php?file=<?php echo $file_item ?>"><?php echo $file_item ?></a>
                                    <a class="btn btn-danger" href="?<?php if ($file) echo "file=" . $file ?>&delete=<?php echo $file_item ?>">Eliminar</a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para buscar un archivo -->
        <div class="modal fade" id="searchModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="search.php" method="GET">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="searchModalLabel">Busca un archivo</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="text" placeholder="merece un 20 profe.txt" class="form-control" id="search" name="search" aria-describedby="search" required>
                            <div class="form-text">EASTER EGG: Si logras enviar el formulario sin escribir nada buscaras todos los archivos, lo cual es lo mismo a usar la opción de "Abrir archivo"</div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary">Buscar coincidencias</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <?php if ($isLoaded && !empty($file)) { ?>
            <h2 class="h1 m-0"><?php echo $file ?></h2>
            <form class="flex-grow-1 d-flex flex-column" style="gap: 1rem" method="POST">
                <textarea placeholder="Aquí puedes escribir texto!!" class="h-100 form-control flex-grow-1 shadow-sm bg-body-tertiary rounded" name="content" style="resize: none"><?php echo file_get_contents($dir . $file); ?></textarea>
                <input type="hidden" name="filename" value="<?php echo $file ?>">
                <button name="save" type="submit" class="btn btn-primary">Guardar</button>
            </form>
        <?php } else { ?>
            <form class="flex-grow-1 d-flex flex-column" method="POST">
                <textarea placeholder="Aquí puedes escribir texto!!" class="h-100 form-control flex-grow-1 shadow bg-body-tertiary rounded" style="resize: none" name="content"></textarea>
                <!-- Modal para guardar un archivo -->
                <div class="modal fade" id="saveModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="saveModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="saveModalLabel">Nombre del archivo</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="text" placeholder="merece un 20 profe.txt" class="form-control" id="filename" name="filename" aria-describedby="filename" required>
                                <div id="filename" class="form-text">NOTA: No es necesario colocar la extensión del archivo.</div>
                            </div>
                            <div class="modal-footer">
                                <button name="save" type="submit" class="btn btn-primary">Guardar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        <?php } ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</body>

</html>
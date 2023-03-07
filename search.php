<?php
$isDeleted = false;

$search = '';
$dir = './files/';

if (isset($_GET['search'])) {
  $search = $_GET['search'];
}

// Si se ha solicitado la eliminación de un archivo
if (isset($_GET["delete"]) && !empty($_GET['delete'])) {
  $filename = $_GET['delete'];
  if (file_exists($dir . $filename)) {
    unlink($dir . $filename);
    $isDeleted = true;
  }
}

// Se ha solicitado el listado de los archivos en el directorio
$files = scandir($dir);
$files = array_diff($files, array('.', '..'));
$files = array_filter($files, function ($file) use ($search) {
  return strpos($file, $search) !== false;
})
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bloc de notas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
</head>

<body>
  <div class="container-fluid min-vh-100 d-flex flex-column align-items-center pt-3 pb-3" style="gap: 1rem">
    <?php if ($isDeleted) { ?>
      <div class="alert alert-danger m-0" role="alert">
        ¡El archivo a sido eliminado!
      </div>
    <?php } ?>


    <!-- Titulo -->
    <h1 class="display-3 m-0 text-center text-uppercase fw-bold">Bloc de notas</h1>

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

    <!-- Resultados -->
    <div class="flex-grow-1 d-flex flex-column" style="max-width: 700px; gap: 1rem">
      <h2 class="h1 m-0">Resultados de la busqueda con: <?php echo $search ?></h2>
      <div>
        <a class="btn btn-primary" href="./index.php">Nuevo archivo</a>
        <button type="button" class="flex-grow-1 btn btn-primary" data-bs-toggle="modal" data-bs-target="#searchModal">
          Buscar de nuevo
        </button>
      </div>
      <?php if (empty($files)) { ?>
        <p class="display-6">No se han encontrado coincidencias.</p>
      <?php } else { ?>
        <ul class="shadow p-3 bg-body-tertiary rounded d-flex flex-column m-0 p-0" style="gap: 1rem">
          <?php foreach ($files as $file_item) { ?>
            <li class="d-flex justify-content-between align-items-center">
              <a href="index.php?file=<?php echo $file_item ?>"><?php echo $file_item ?></a>
              <a class="btn btn-danger" href="?search=<?php echo $search ?>&delete=<?php echo $file_item ?>">Eliminar</a>
            </li>
          <?php } ?>
        </ul>
      <?php } ?>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</body>

</html>
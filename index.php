<?php
require_once 'clases/Pagina.php';

$pagina = new Pagina('Menú principal');
$pagina->encabezado();
?>
    <p>Selecciona una de las aplicaciones:</p>

    <div class="tarjetas">
        <?php foreach ($pagina->getAplicaciones() as $numero => $app): ?>
            <a class="tarjeta" href="apps/<?= $app['carpeta'] ?>/index.php">
                <span class="tarjeta-numero"><?= $numero + 1 ?></span>
                <h2><?= $app['nombre'] ?></h2>
                <p><?= $app['descripcion'] ?></p>
            </a>
        <?php endforeach; ?>
    </div>
<?php
$pagina->pie();

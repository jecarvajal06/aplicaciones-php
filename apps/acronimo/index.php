<?php
require_once '../../clases/Pagina.php';
require_once 'Acronimo.php';

$pagina = new Pagina('Acrónimos', '../../');
$pagina->encabezado();

$frase = isset($_POST['frase']) ? $_POST['frase'] : '';
$error = '';
$resultado = '';
$palabras = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $acronimo = new Acronimo($frase);
        $resultado = $acronimo->getAcronimo();
        $palabras = $acronimo->getPalabras();
    } catch (InvalidArgumentException $e) {
        $error = $e->getMessage();
    }
}

$ejemplos = ['As Soon As Possible', 'Liquid-crystal display', "Thank George It's Friday!"];
?>
<section class="caja">
    <form method="post" action="index.php">
        <div class="campo">
            <label for="frase">Frase:</label>
            <input type="text" id="frase" name="frase" value="<?= htmlspecialchars($frase) ?>">
        </div>
        <p class="ayuda">Los guiones separan palabras; los demás signos de puntuación se ignoran.</p>
        <button class="boton" type="submit">Obtener acrónimo</button>
    </form>
</section>

<section class="caja">
    <p>Ejemplos:</p>
    <?php foreach ($ejemplos as $ejemplo): ?>
        <form method="post" action="index.php" style="display:inline">
            <input type="hidden" name="frase" value="<?= htmlspecialchars($ejemplo) ?>">
            <button class="boton-secundario" type="submit"><?= htmlspecialchars($ejemplo) ?></button>
        </form>
    <?php endforeach; ?>
</section>

<?php if ($error !== ''): ?>
    <p class="error"><?= htmlspecialchars($error) ?></p>
<?php elseif ($resultado !== ''): ?>
    <section class="caja resultado">
        <p>Acrónimo:</p>
        <p class="resultado-valor"><?= htmlspecialchars($resultado) ?></p>
        <p>Palabras detectadas: <?= htmlspecialchars(implode(' · ', $palabras)) ?></p>
    </section>
<?php endif; ?>
<?php
$pagina->pie();

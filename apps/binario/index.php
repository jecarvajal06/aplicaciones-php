<?php
require_once '../../clases/Pagina.php';
require_once 'ConversorBinario.php';

$pagina = new Pagina('Decimal a binario', '../../');
$pagina->encabezado();

$entrada = isset($_POST['numero']) ? $_POST['numero'] : '';
$error = '';
$binario = '';
$agrupado = '';
$pasos = [];
$negativo = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $conversor = new ConversorBinario($entrada);
        $binario = $conversor->convertir();
        $agrupado = $conversor->getAgrupado();
        $pasos = $conversor->getPasos();
        $negativo = $conversor->esNegativo();
    } catch (InvalidArgumentException $e) {
        $error = $e->getMessage();
    }
}
?>
<section class="caja">
    <form method="post" action="index.php">
        <div class="campo">
            <label for="numero">Número entero:</label>
            <input type="text" id="numero" name="numero" value="<?= htmlspecialchars($entrada) ?>">
        </div>
        <p class="ayuda">Entero de hasta <?= PHP_INT_MAX ?> (puede ser negativo).</p>
        <button class="boton" type="submit">Convertir</button>
    </form>
</section>

<?php if ($error !== ''): ?>
    <p class="error"><?= htmlspecialchars($error) ?></p>
<?php elseif ($binario !== ''): ?>
    <section class="caja resultado">
        <p>Binario de <?= htmlspecialchars(trim($entrada)) ?>:</p>
        <p class="resultado-valor"><?= htmlspecialchars($binario) ?></p>
        <p>Agrupado de a 4 bits: <strong><?= htmlspecialchars($agrupado) ?></strong></p>
        <?php if ($negativo): ?>
            <p class="ayuda">Nota: se muestra el signo menos delante del binario del valor absoluto; no se usa complemento a dos.</p>
        <?php endif; ?>
    </section>

    <section class="caja">
        <h2>Procedimiento</h2>
        <?php if (count($pasos) > 0): ?>
            <table>
                <thead>
                    <tr><th>Dividendo</th><th>Cociente</th><th>Residuo</th></tr>
                </thead>
                <tbody>
                <?php foreach ($pasos as $paso): ?>
                    <tr>
                        <td><?= $paso['dividendo'] ?> ÷ 2</td>
                        <td><?= $paso['cociente'] ?></td>
                        <td><?= $paso['residuo'] ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <p class="ayuda">El binario se lee de abajo hacia arriba (columna de residuos).</p>
        <?php else: ?>
            <p>El 0 es 0 en binario; no hay divisiones que hacer.</p>
        <?php endif; ?>
    </section>
<?php endif; ?>
<?php
$pagina->pie();

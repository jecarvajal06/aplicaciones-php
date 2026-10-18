<?php
require_once '../../clases/Pagina.php';
require_once 'Fibonacci.php';
require_once 'Factorial.php';

$pagina = new Pagina('Fibonacci y factorial', '../../');

$numero = isset($_POST['numero']) ? trim((string) $_POST['numero']) : '';
$tipo = isset($_POST['operacion']) ? $_POST['operacion'] : 'fibonacci';
$error = '';
$operacion = null;
$serie = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Se elige la clase según el select (polimorfismo: ambas se usan igual)
    if ($tipo === 'factorial') {
        $operacion = new Factorial($numero);
    } elseif ($tipo === 'fibonacci') {
        $operacion = new Fibonacci($numero);
    } else {
        $error = 'Operación no válida.';
    }

    if ($operacion !== null) {
        $error = $operacion->validar();
        if ($error === '') {
            $serie = $operacion->calcularSerie();
        }
    }
}

$pagina->encabezado();
?>
<form method="post" action="index.php" class="caja">
    <div class="campo">
        <label for="numero">Número</label>
        <input type="text" id="numero" name="numero" value="<?= htmlspecialchars($numero) ?>">
        <p class="ayuda">Entero no negativo. Fibonacci: de 1 a 93 términos. Factorial: de 0 a 20.</p>
    </div>
    <div class="campo">
        <label for="operacion">Operación</label>
        <select id="operacion" name="operacion">
            <option value="fibonacci"<?= $tipo === 'fibonacci' ? ' selected' : '' ?>>Sucesión de Fibonacci</option>
            <option value="factorial"<?= $tipo === 'factorial' ? ' selected' : '' ?>>Factorial</option>
        </select>
    </div>
    <button type="submit" class="boton">Calcular</button>
</form>

<?php if ($error !== ''): ?>
    <p class="error"><?= htmlspecialchars($error) ?></p>
<?php elseif ($operacion !== null): ?>
    <div class="caja resultado">
        <h2><?= htmlspecialchars($operacion->getNombre()) ?> de <?= htmlspecialchars((string) (int) $numero) ?></h2>
        <?php if ($operacion instanceof Factorial): ?>
            <p class="resultado-valor"><?= htmlspecialchars($operacion->getExpresion()) ?></p>
            <table>
                <thead><tr><th>n</th><th>n!</th></tr></thead>
                <tbody>
                <?php if ((int) $numero === 0): ?>
                    <tr><td>0</td><td>1</td></tr>
                <?php else: ?>
                    <?php foreach ($serie as $i => $valor): ?>
                        <tr><td><?= $i + 1 ?></td><td><?= $valor ?></td></tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="resultado-valor"><?= implode(', ', $serie) ?></p>
            <table>
                <thead><tr><th>Posición</th><th>Término</th></tr></thead>
                <tbody>
                <?php foreach ($serie as $i => $valor): ?>
                    <tr><td><?= $i + 1 ?></td><td><?= $valor ?></td></tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
<?php endif; ?>
<?php $pagina->pie(); ?>

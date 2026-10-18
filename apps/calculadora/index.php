<?php
require_once '../../clases/Pagina.php';
require_once 'Calculadora.php';
require_once 'Operacion.php';
require_once 'Historial.php';

session_start(); // antes de imprimir el encabezado
date_default_timezone_set('America/Bogota'); // hora de Colombia en el historial

$historial = new Historial();

// Procesar el formulario y redirigir (POST-redirect-GET) para que
// recargar la página no repita la operación.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = isset($_POST['operacion']) ? $_POST['operacion'] : '';
    $num1 = isset($_POST['num1']) ? trim($_POST['num1']) : '';
    $num2 = isset($_POST['num2']) ? trim($_POST['num2']) : '';
    unset($_SESSION['calc_error'], $_SESSION['calc_resultado']);

    if ($accion === 'borrar') {
        $historial->borrar();
        $_SESSION['calc_aviso'] = 'Historial borrado.';
    } else {
        $_SESSION['calc_num1'] = $num1;
        $_SESSION['calc_num2'] = $num2;
        try {
            $calculadora = new Calculadora($num1, $num2);
            $resultado = $calculadora->calcular($accion);
            $operacion = new Operacion((float)$num1, (float)$num2, $accion, $resultado);
            $historial->agregar($operacion);
            $_SESSION['calc_resultado'] = $operacion->getTexto();
        } catch (InvalidArgumentException $e) {
            $_SESSION['calc_error'] = $e->getMessage();
        }
    }
    header('Location: index.php');
    exit;
}

// Leer (una sola vez) lo que dejó el procesamiento anterior
$error = isset($_SESSION['calc_error']) ? $_SESSION['calc_error'] : '';
$resultado = isset($_SESSION['calc_resultado']) ? $_SESSION['calc_resultado'] : '';
$aviso = isset($_SESSION['calc_aviso']) ? $_SESSION['calc_aviso'] : '';
$num1 = isset($_SESSION['calc_num1']) ? $_SESSION['calc_num1'] : '';
$num2 = isset($_SESSION['calc_num2']) ? $_SESSION['calc_num2'] : '';
unset($_SESSION['calc_error'], $_SESSION['calc_resultado'], $_SESSION['calc_aviso']);

$pagina = new Pagina('Calculadora', '../../');
$pagina->encabezado();
?>
<section class="caja">
    <form method="post" action="index.php">
        <div class="campo">
            <label for="num1">Número 1 (A):</label>
            <input type="number" step="any" id="num1" name="num1" value="<?= htmlspecialchars($num1) ?>" required>
        </div>
        <div class="campo">
            <label for="num2">Número 2 (B):</label>
            <input type="number" step="any" id="num2" name="num2" value="<?= htmlspecialchars($num2) ?>" required>
        </div>
        <button class="boton" type="submit" name="operacion" value="suma">+</button>
        <button class="boton" type="submit" name="operacion" value="resta">−</button>
        <button class="boton" type="submit" name="operacion" value="multiplicacion">×</button>
        <button class="boton" type="submit" name="operacion" value="division">÷</button>
        <button class="boton" type="submit" name="operacion" value="porcentaje">%</button>
        <p class="ayuda">El botón % calcula: ¿Cuánto es A% de B? (A × B / 100).</p>
    </form>
</section>

<?php if ($error !== ''): ?>
    <p class="error"><?= htmlspecialchars($error) ?></p>
<?php elseif ($resultado !== ''): ?>
    <section class="caja resultado">
        <p>Resultado:</p>
        <p class="resultado-valor"><?= htmlspecialchars($resultado) ?></p>
    </section>
<?php endif; ?>
<?php if ($aviso !== ''): ?>
    <p class="ayuda"><?= htmlspecialchars($aviso) ?></p>
<?php endif; ?>

<section class="caja">
    <h2>Historial</h2>
    <?php $operaciones = $historial->obtenerTodas(); ?>
    <?php if (count($operaciones) === 0): ?>
        <p class="ayuda">Aún no hay operaciones.</p>
    <?php else: ?>
        <table>
            <tr><th>Hora</th><th>Operación</th></tr>
            <?php foreach ($operaciones as $op): ?>
                <tr>
                    <td><?= htmlspecialchars($op->getHora()) ?></td>
                    <td><?= htmlspecialchars($op->getTexto()) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <form method="post" action="index.php">
            <button class="boton-secundario" type="submit" name="operacion" value="borrar" formnovalidate>Borrar historial</button>
        </form>
    <?php endif; ?>
</section>
<?php
$pagina->pie();

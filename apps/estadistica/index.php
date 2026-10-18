<?php
require_once '../../clases/Pagina.php';
require_once 'Estadistica.php';

const MAX_NUMEROS = 50;

$errores = [];
$cantidad = null;     // cantidad de números que el usuario va a ingresar
$valores = [];        // textos escritos en los campos (paso 2)
$estadistica = null;  // se crea cuando hay resultados

$accion = isset($_POST['accion']) ? $_POST['accion'] : '';

if ($accion === 'cantidad' || $accion === 'calcular') {
    $texto = isset($_POST['cantidad']) ? trim((string) $_POST['cantidad']) : '';
    if (!preg_match('/^\d+$/', $texto) || (int) $texto < 1 || (int) $texto > MAX_NUMEROS) {
        $errores[] = 'La cantidad debe ser un entero entre 1 y ' . MAX_NUMEROS . '.';
    } else {
        $cantidad = (int) $texto;
    }
}

// Paso 2: leer y validar los números ingresados
if ($accion === 'calcular' && $cantidad !== null) {
    $recibidos = (isset($_POST['numeros']) && is_array($_POST['numeros'])) ? $_POST['numeros'] : [];
    $numeros = [];
    for ($i = 0; $i < $cantidad; $i++) {
        $crudo = isset($recibidos[$i]) && is_string($recibidos[$i]) ? trim($recibidos[$i]) : '';
        $valores[$i] = $crudo;
        $limpio = str_replace(',', '.', $crudo); // se acepta coma decimal
        if ($crudo === '') {
            $errores[] = 'El número ' . ($i + 1) . ' está vacío.';
        } elseif (!preg_match('/^[+-]?(\d+(\.\d*)?|\.\d+)$/', $limpio) || !is_finite((float) $limpio)) {
            $errores[] = 'El número ' . ($i + 1) . ' no es un número real válido.';
        } else {
            $numeros[] = (float) $limpio;
        }
    }
    if (count($errores) === 0) {
        $estadistica = new Estadistica($numeros);
    }
}

$pagina = new Pagina('Promedio, mediana y moda', '../../');
$pagina->encabezado();
?>
<p>Calcula el promedio, la mediana y la moda de una serie de números reales (se aceptan negativos y decimales).</p>
<p class="ayuda">Nota: el enunciado pide "promedio" y "media", pero son lo mismo (el promedio es la media aritmética).
    Por eso la "media" del enunciado se toma aquí como la <strong>mediana</strong>.</p>

<?php foreach ($errores as $error): ?>
    <p class="error"><?= htmlspecialchars($error) ?></p>
<?php endforeach; ?>

<?php if ($estadistica !== null): ?>
    <?php
    $moda = $estadistica->moda();
    $modaTexto = count($moda) === 0
        ? 'No hay moda (todos los valores se repiten la misma cantidad de veces)'
        : implode(', ', array_map('Estadistica::formatear', $moda));
    ?>
    <div class="caja resultado">
        <p>Promedio (media aritmética): <span class="resultado-valor"><?= Estadistica::formatear($estadistica->promedio()) ?></span></p>
        <p>Mediana: <span class="resultado-valor"><?= Estadistica::formatear($estadistica->mediana()) ?></span></p>
        <p>Moda<?= count($moda) > 1 ? 's (multimodal)' : '' ?>: <span class="resultado-valor"><?= htmlspecialchars($modaTexto) ?></span></p>
        <p>Datos ordenados:
            <span class="resultado-valor"><?= htmlspecialchars(implode('; ', array_map('Estadistica::formatear', $estadistica->ordenados()))) ?></span></p>
    </div>

    <div class="caja">
        <h2>Tabla de frecuencias</h2>
        <table>
            <tr><th>Valor</th><th>Frecuencia</th></tr>
            <?php foreach ($estadistica->frecuencias() as $fila): ?>
                <tr>
                    <td><?= Estadistica::formatear($fila['valor']) ?></td>
                    <td><?= $fila['veces'] ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <a class="boton boton-secundario" href="index.php">Empezar de nuevo</a>

<?php elseif ($cantidad !== null): ?>
    <form class="caja" method="post" action="index.php">
        <input type="hidden" name="accion" value="calcular">
        <input type="hidden" name="cantidad" value="<?= $cantidad ?>">
        <p>Ingresa los <?= $cantidad ?> número(s):</p>
        <?php for ($i = 0; $i < $cantidad; $i++): ?>
            <div class="campo">
                <label for="n<?= $i ?>">Número <?= $i + 1 ?></label>
                <input type="number" step="any" id="n<?= $i ?>" name="numeros[]" required
                       value="<?= htmlspecialchars(isset($valores[$i]) ? $valores[$i] : '') ?>">
            </div>
        <?php endfor; ?>
        <button class="boton" type="submit">Calcular</button>
        <a class="boton boton-secundario" href="index.php">Empezar de nuevo</a>
    </form>

<?php else: ?>
    <form class="caja" method="post" action="index.php">
        <input type="hidden" name="accion" value="cantidad">
        <div class="campo">
            <label for="cantidad">¿Cuántos números vas a ingresar?</label>
            <input type="number" id="cantidad" name="cantidad" min="1" max="<?= MAX_NUMEROS ?>" step="1" required
                   value="<?= htmlspecialchars(isset($_POST['cantidad']) ? (string) $_POST['cantidad'] : '') ?>">
            <span class="ayuda">Entre 1 y <?= MAX_NUMEROS ?>.</span>
        </div>
        <button class="boton" type="submit">Continuar</button>
    </form>
<?php endif; ?>
<?php $pagina->pie(); ?>

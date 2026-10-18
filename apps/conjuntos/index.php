<?php
require_once '../../clases/Pagina.php';
require_once 'Conjunto.php';

$pagina = new Pagina('Conjuntos', '../../', 'estilo.css');
$pagina->encabezado();

$textoA = isset($_POST['a']) ? (string) $_POST['a'] : '';
$textoB = isset($_POST['b']) ? (string) $_POST['b'] : '';
$error = '';
$A = null;
$B = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $A = Conjunto::desdeTexto($textoA, 'A');
        $B = Conjunto::desdeTexto($textoB, 'B');
    } catch (InvalidArgumentException $e) {
        $error = $e->getMessage();
        $A = null;
        $B = null;
    }
}
?>

<section class="caja">
    <form method="post" action="index.php">
        <div class="campo">
            <label for="a">Conjunto A:</label>
            <input type="text" id="a" name="a" value="<?= htmlspecialchars($textoA) ?>" placeholder="Ejemplo: 1, 2, 3, 4">
        </div>
        <div class="campo">
            <label for="b">Conjunto B:</label>
            <input type="text" id="b" name="b" value="<?= htmlspecialchars($textoB) ?>" placeholder="Ejemplo: 3, 4, 5, -6">
        </div>
        <p class="ayuda">Escribe números enteros (se permiten negativos) separados por comas, espacios o punto y coma.</p>
        <button class="boton" type="submit">Calcular</button>
    </form>
</section>

<?php if ($error !== ''): ?>
    <p class="error"><?= htmlspecialchars($error) ?></p>
<?php elseif ($A !== null): ?>
    <?php
    $union = $A->union($B);
    $interseccion = $A->interseccion($B);
    $difAB = $A->diferencia($B);
    $difBA = $B->diferencia($A);
    ?>
    <?php foreach (['A' => $A, 'B' => $B] as $nombre => $conjunto): ?>
        <?php if ($conjunto->getRepetidos() > 0): ?>
            <p class="ayuda">Aviso: en el conjunto <?= $nombre ?> se eliminaron <?= $conjunto->getRepetidos() ?> elemento(s) repetido(s).</p>
        <?php endif; ?>
    <?php endforeach; ?>

    <section class="caja resultado">
        <table>
            <tr><th>Conjunto</th><th>Elementos</th></tr>
            <tr><td>A</td><td><?= htmlspecialchars($A->mostrar()) ?></td></tr>
            <tr><td>B</td><td><?= htmlspecialchars($B->mostrar()) ?></td></tr>
            <tr><td>A ∪ B (unión)</td><td><?= htmlspecialchars($union->mostrar()) ?></td></tr>
            <tr><td>A ∩ B (intersección)</td><td><?= htmlspecialchars($interseccion->mostrar()) ?></td></tr>
            <tr><td>A − B</td><td><?= htmlspecialchars($difAB->mostrar()) ?></td></tr>
            <tr><td>B − A</td><td><?= htmlspecialchars($difBA->mostrar()) ?></td></tr>
        </table>
    </section>

    <section class="caja">
        <h2>Diagrama de Venn</h2>
        <div class="venn">
            <div class="venn-contenedor">
                <div class="venn-circulo venn-a">
                    <span class="venn-etiqueta">A − B</span>
                    <span><?= htmlspecialchars($difAB->mostrar()) ?></span>
                </div>
                <div class="venn-circulo venn-b">
                    <span class="venn-etiqueta">B − A</span>
                    <span><?= htmlspecialchars($difBA->mostrar()) ?></span>
                </div>
                <div class="venn-centro"><?= htmlspecialchars($interseccion->mostrar()) ?></div>
            </div>
        </div>
        <p class="ayuda">Al centro está la intersección A ∩ B.</p>
    </section>
<?php endif; ?>
<?php
$pagina->pie();

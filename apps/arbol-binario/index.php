<?php
require_once '../../clases/Pagina.php';
require_once 'ConstructorArbol.php';

$pagina = new Pagina('Árbol binario', '../../', 'estilo.css');
$pagina->encabezado();

$pre = isset($_POST['pre']) && is_string($_POST['pre']) ? $_POST['pre'] : '';
$in = isset($_POST['in']) && is_string($_POST['in']) ? $_POST['in'] : '';
$post = isset($_POST['post']) && is_string($_POST['post']) ? $_POST['post'] : '';

$error = '';
$constructor = null;
$arbol = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $constructor = new ConstructorArbol(['pre' => $pre, 'in' => $in, 'post' => $post]);
        $arbol = $constructor->construir();
    } catch (InvalidArgumentException $e) {
        $error = $e->getMessage();
    }
}

$ejemplo = ['pre' => 'A B D E C', 'in' => 'D B E A C', 'post' => 'D E B C A'];
?>

<section class="caja">
    <form method="post" action="index.php">
        <div class="campo">
            <label for="pre">Preorden:</label>
            <input type="text" id="pre" name="pre" value="<?= htmlspecialchars($pre) ?>" placeholder="A B D E C">
        </div>
        <div class="campo">
            <label for="in">Inorden:</label>
            <input type="text" id="in" name="in" value="<?= htmlspecialchars($in) ?>" placeholder="D B E A C">
        </div>
        <div class="campo">
            <label for="post">Postorden:</label>
            <input type="text" id="post" name="post" value="<?= htmlspecialchars($post) ?>" placeholder="D E B C A">
        </div>
        <p class="ayuda">Ingresa al menos dos recorridos. Separa los nodos con espacios, comas o flechas (→ o ->).
            Los nodos pueden ser letras o números y no se pueden repetir.
            Si ingresas los tres, se usa preorden + inorden.</p>
        <button class="boton" type="submit">Construir árbol</button>
    </form>
</section>

<section class="caja">
    <p>Ejemplo del enunciado:</p>
    <?php
    $combinaciones = [
        'Preorden + inorden' => ['pre', 'in'],
        'Postorden + inorden' => ['post', 'in'],
        'Preorden + postorden' => ['pre', 'post'],
        'Los tres' => ['pre', 'in', 'post'],
    ];
    foreach ($combinaciones as $nombre => $campos): ?>
        <form method="post" action="index.php" style="display:inline">
            <?php foreach ($campos as $c): ?>
                <input type="hidden" name="<?= $c ?>" value="<?= htmlspecialchars($ejemplo[$c]) ?>">
            <?php endforeach; ?>
            <button class="boton-secundario" type="submit"><?= htmlspecialchars($nombre) ?></button>
        </form>
    <?php endforeach; ?>
</section>

<?php if ($error !== ''): ?>
    <p class="error"><?= htmlspecialchars($error) ?></p>
<?php elseif ($arbol !== null): ?>
    <section class="caja resultado">
        <p><strong>Par usado:</strong> <?= htmlspecialchars($constructor->getParUsado()) ?>.
            <?= htmlspecialchars($constructor->getMotivo()) ?></p>
        <?php foreach ($constructor->getAvisos() as $aviso): ?>
            <p class="aviso"><strong>Aviso:</strong> <?= htmlspecialchars($aviso) ?></p>
        <?php endforeach; ?>
    </section>

    <section class="caja">
        <h2>Árbol construido</h2>
        <?php $dibujo = new DibujoArbol(); echo $dibujo->generarHtml($arbol->getRaiz()); ?>
        <p class="ayuda">Los recuadros punteados son huecos: indican que ese hijo no existe.</p>
    </section>

    <section class="caja">
        <h2>Recorridos del árbol</h2>
        <table>
            <tr><th>Preorden</th><td><?= htmlspecialchars(implode(' ', $arbol->preorden())) ?></td></tr>
            <tr><th>Inorden</th><td><?= htmlspecialchars(implode(' ', $arbol->inorden())) ?></td></tr>
            <tr><th>Postorden</th><td><?= htmlspecialchars(implode(' ', $arbol->postorden())) ?></td></tr>
        </table>
    </section>
<?php endif; ?>
<?php
$pagina->pie();

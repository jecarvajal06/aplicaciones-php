<?php
require_once 'ArbolBinario.php';
require_once 'DibujoArbol.php';

/**
 * Clase ConstructorArbol
 * Recibe los textos de los tres recorridos, los convierte en listas de nodos,
 * decide qué par usar y construye el árbol.
 */
class ConstructorArbol
{
    private $textos;
    private $arbol = null;
    private $parUsado = '';
    private $motivo = '';
    private $avisos = [];

    // $textos: ['pre' => '...', 'in' => '...', 'post' => '...']
    public function __construct(array $textos)
    {
        $this->textos = $textos;
    }

    // Separa por espacios, comas o flechas (→ y ->) y descarta vacíos
    public static function separar($texto)
    {
        $partes = preg_split('/->|→|[\s,]+/u', (string) $texto);
        $nodos = [];
        foreach ($partes as $parte) {
            $parte = trim($parte);
            if ($parte !== '') {
                $nodos[] = $parte;
            }
        }
        return $nodos;
    }

    public function getArbol()
    {
        return $this->arbol;
    }

    public function getParUsado()
    {
        return $this->parUsado;
    }

    public function getMotivo()
    {
        return $this->motivo;
    }

    public function getAvisos()
    {
        return $this->avisos;
    }

    // Construye el árbol; lanza InvalidArgumentException si hay algún problema
    public function construir()
    {
        $pre = self::separar(isset($this->textos['pre']) ? $this->textos['pre'] : '');
        $in = self::separar(isset($this->textos['in']) ? $this->textos['in'] : '');
        $post = self::separar(isset($this->textos['post']) ? $this->textos['post'] : '');

        $hayPre = count($pre) > 0;
        $hayIn = count($in) > 0;
        $hayPost = count($post) > 0;

        if (($hayPre ? 1 : 0) + ($hayIn ? 1 : 0) + ($hayPost ? 1 : 0) < 2) {
            throw new InvalidArgumentException('Ingresa al menos dos recorridos para construir el árbol.');
        }

        $arbol = new ArbolBinario();
        if ($hayPre && $hayIn) {
            $arbol->construirDesdePreIn($pre, $in);
            $this->parUsado = 'Preorden + inorden';
            $this->motivo = $hayPost
                ? 'Se ingresaron los tres recorridos y por defecto se usa preorden + inorden.'
                : 'Son los dos recorridos ingresados.';
            if ($hayPost) {
                $this->verificarPostorden($arbol, $post);
            }
        } elseif ($hayPost && $hayIn) {
            $arbol->construirDesdePostIn($post, $in);
            $this->parUsado = 'Postorden + inorden';
            $this->motivo = 'Son los dos recorridos ingresados.';
        } else {
            $arbol->construirDesdePrePost($pre, $post);
            $this->parUsado = 'Preorden + postorden';
            $this->motivo = 'Son los dos recorridos ingresados.';
        }

        $this->avisos = array_merge($this->avisos, $arbol->getAvisos());
        $this->arbol = $arbol;
        return $arbol;
    }

    // Con los tres recorridos: el postorden dado debe ser el del árbol construido
    private function verificarPostorden(ArbolBinario $arbol, array $post)
    {
        if ($arbol->postorden() !== $post) {
            $this->avisos[] = 'El postorden ingresado (' . implode(' ', $post)
                . ') no coincide con el del árbol construido con preorden + inorden ('
                . implode(' ', $arbol->postorden()) . '). Se ignoró el postorden ingresado.';
        }
    }
}

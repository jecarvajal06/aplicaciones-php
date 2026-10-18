<?php
require_once 'Nodo.php';

/**
 * Clase ArbolBinario
 * Construye un árbol binario a partir de dos de sus recorridos
 * (preorden-inorden, postorden-inorden o preorden-postorden)
 * y calcula los tres recorridos para poder verificarlo.
 */
class ArbolBinario
{
    const MAX_NODOS = 300;

    private $raiz = null;
    private $avisos = [];

    // Auxiliares de la construcción (posición de cada valor en un recorrido)
    private $posA = [];
    private $posB = [];
    private $recA = [];
    private $recB = [];

    public function getRaiz()
    {
        return $this->raiz;
    }

    public function getAvisos()
    {
        return $this->avisos;
    }

    // ---------------------------------------------------------------
    // Construcciones
    // ---------------------------------------------------------------

    public function construirDesdePreIn(array $pre, array $in)
    {
        $this->prepararPar($pre, $in);
        $this->raiz = $this->armarPreIn(0, 0, count($pre));
        $this->comprobar($pre, $in, null);
        return $this;
    }

    public function construirDesdePostIn(array $post, array $in)
    {
        $this->prepararPar($post, $in);
        $this->raiz = $this->armarPostIn(0, 0, count($post));
        $this->comprobar(null, $in, $post);
        return $this;
    }

    public function construirDesdePrePost(array $pre, array $post)
    {
        $this->prepararPar($pre, $post);
        $this->raiz = $this->armarPrePost(0, 0, count($pre));
        $this->comprobar($pre, null, $post);
        return $this;
    }

    // Valida el par de recorridos y guarda lo necesario para armar el árbol
    private function prepararPar(array $a, array $b)
    {
        $this->avisos = [];
        $this->raiz = null;
        $a = array_values($a);
        $b = array_values($b);
        $this->validarPar($a, $b);
        $this->recA = $a;
        $this->recB = $b;
        $this->posA = $this->posiciones($a);
        $this->posB = $this->posiciones($b);
    }

    private function validarPar(array $a, array $b)
    {
        if (count($a) === 0 || count($b) === 0) {
            throw new InvalidArgumentException('Los recorridos no pueden estar vacíos.');
        }
        foreach ([$a, $b] as $lista) {
            $vistos = [];
            foreach ($lista as $v) {
                $clave = (string) $v;
                if (isset($vistos[$clave])) {
                    throw new InvalidArgumentException("El nodo «{$v}» está repetido: los nodos no pueden repetirse.");
                }
                $vistos[$clave] = true;
            }
        }
        if (count($a) !== count($b)) {
            throw new InvalidArgumentException('Los recorridos tienen distinta cantidad de nodos ('
                . count($a) . ' y ' . count($b) . ').');
        }
        if (count($a) > self::MAX_NODOS) {
            throw new InvalidArgumentException('El árbol es demasiado grande (máximo ' . self::MAX_NODOS . ' nodos).');
        }
        $posB = $this->posiciones($b);
        foreach ($a as $v) {
            if (!isset($posB[(string) $v])) {
                throw new InvalidArgumentException("El nodo «{$v}» aparece en un recorrido pero no en el otro.");
            }
        }
    }

    private function posiciones(array $lista)
    {
        $pos = [];
        foreach ($lista as $i => $v) {
            $pos[(string) $v] = $i;
        }
        return $pos;
    }

    private function inconsistente()
    {
        return new InvalidArgumentException(
            'Los recorridos son inconsistentes: no corresponden a ningún árbol binario.'
        );
    }

    // Preorden (A) + inorden (B). $ia = inicio en preorden, $ib = inicio en inorden
    private function armarPreIn($ia, $ib, $n)
    {
        if ($n <= 0) {
            return null;
        }
        $valor = $this->recA[$ia];
        $p = $this->posB[(string) $valor];
        if ($p < $ib || $p >= $ib + $n) {
            throw $this->inconsistente();
        }
        $izq = $p - $ib; // cantidad de nodos del subárbol izquierdo
        $nodo = new Nodo($valor);
        $nodo->setIzquierdo($this->armarPreIn($ia + 1, $ib, $izq));
        $nodo->setDerecho($this->armarPreIn($ia + 1 + $izq, $p + 1, $n - $izq - 1));
        return $nodo;
    }

    // Postorden (A) + inorden (B). $ia = inicio en postorden, $ib = inicio en inorden
    private function armarPostIn($ia, $ib, $n)
    {
        if ($n <= 0) {
            return null;
        }
        $valor = $this->recA[$ia + $n - 1]; // la raíz es el último del postorden
        $p = $this->posB[(string) $valor];
        if ($p < $ib || $p >= $ib + $n) {
            throw $this->inconsistente();
        }
        $izq = $p - $ib;
        $nodo = new Nodo($valor);
        $nodo->setIzquierdo($this->armarPostIn($ia, $ib, $izq));
        $nodo->setDerecho($this->armarPostIn($ia + $izq, $p + 1, $n - $izq - 1));
        return $nodo;
    }

    // Preorden (A) + postorden (B). $ia = inicio en preorden, $ib = inicio en postorden
    private function armarPrePost($ia, $ib, $n)
    {
        if ($n <= 0) {
            return null;
        }
        $valor = $this->recA[$ia];
        if ((string) $this->recB[$ib + $n - 1] !== (string) $valor) {
            throw $this->inconsistente();
        }
        $nodo = new Nodo($valor);
        if ($n === 1) {
            return $nodo;
        }
        // El segundo del preorden es la raíz del subárbol izquierdo; en el
        // postorden marca dónde termina ese subárbol.
        $hijo = $this->recA[$ia + 1];
        $k = $this->posB[(string) $hijo];
        if ($k < $ib || $k > $ib + $n - 2) {
            throw $this->inconsistente();
        }
        $izq = $k - $ib + 1;
        $der = $n - 1 - $izq;
        if ($der === 0) {
            $this->avisos[] = 'El nodo «' . $valor . '» tiene un solo hijo («' . $hijo
                . '»): con preorden y postorden no se sabe si es izquierdo o derecho, '
                . 'así que se colocó a la izquierda (convención estándar).';
        }
        $nodo->setIzquierdo($this->armarPrePost($ia + 1, $ib, $izq));
        $nodo->setDerecho($this->armarPrePost($ia + 1 + $izq, $ib + $izq, $der));
        return $nodo;
    }

    // Verifica que el árbol construido reproduce exactamente los recorridos dados
    private function comprobar($pre, $in, $post)
    {
        if (($pre !== null && $this->preorden() !== array_values($this->comoTexto($pre))) ||
            ($in !== null && $this->inorden() !== array_values($this->comoTexto($in))) ||
            ($post !== null && $this->postorden() !== array_values($this->comoTexto($post)))) {
            throw $this->inconsistente();
        }
    }

    private function comoTexto(array $lista)
    {
        return array_map('strval', $lista);
    }

    // ---------------------------------------------------------------
    // Recorridos (recursivos)
    // ---------------------------------------------------------------

    public function preorden()
    {
        $salida = [];
        $this->recorrerPre($this->raiz, $salida);
        return $salida;
    }

    public function inorden()
    {
        $salida = [];
        $this->recorrerIn($this->raiz, $salida);
        return $salida;
    }

    public function postorden()
    {
        $salida = [];
        $this->recorrerPost($this->raiz, $salida);
        return $salida;
    }

    private function recorrerPre($nodo, array &$salida)
    {
        if ($nodo === null) {
            return;
        }
        $salida[] = (string) $nodo->getValor();
        $this->recorrerPre($nodo->getIzquierdo(), $salida);
        $this->recorrerPre($nodo->getDerecho(), $salida);
    }

    private function recorrerIn($nodo, array &$salida)
    {
        if ($nodo === null) {
            return;
        }
        $this->recorrerIn($nodo->getIzquierdo(), $salida);
        $salida[] = (string) $nodo->getValor();
        $this->recorrerIn($nodo->getDerecho(), $salida);
    }

    private function recorrerPost($nodo, array &$salida)
    {
        if ($nodo === null) {
            return;
        }
        $this->recorrerPost($nodo->getIzquierdo(), $salida);
        $this->recorrerPost($nodo->getDerecho(), $salida);
        $salida[] = (string) $nodo->getValor();
    }
}

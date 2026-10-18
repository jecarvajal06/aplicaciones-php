<?php
/**
 * Clase Nodo
 * Un nodo del árbol binario: guarda un valor (texto) y sus dos hijos.
 */
class Nodo
{
    private $valor;
    private $izquierdo;
    private $derecho;

    public function __construct($valor, $izquierdo = null, $derecho = null)
    {
        $this->valor = $valor;
        $this->izquierdo = $izquierdo;
        $this->derecho = $derecho;
    }

    public function getValor()
    {
        return $this->valor;
    }

    public function getIzquierdo()
    {
        return $this->izquierdo;
    }

    public function getDerecho()
    {
        return $this->derecho;
    }

    public function setIzquierdo($nodo)
    {
        $this->izquierdo = $nodo;
    }

    public function setDerecho($nodo)
    {
        $this->derecho = $nodo;
    }

    public function esHoja()
    {
        return $this->izquierdo === null && $this->derecho === null;
    }
}

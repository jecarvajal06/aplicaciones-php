<?php
require_once 'Operacion.php';

/**
 * Clase Fibonacci
 * Genera los primeros n términos de la sucesión: 0, 1, 1, 2, 3, 5, 8...
 */
class Fibonacci extends Operacion
{
    const LIMITE = 93;

    public function getNombre()
    {
        return 'Fibonacci';
    }

    public function getLimite()
    {
        return self::LIMITE;
    }

    protected function mensajeLimite()
    {
        return 'Para Fibonacci el número debe estar entre 1 y ' . self::LIMITE
            . ': el término 94 ya no cabe en un entero de 64 bits de PHP.';
    }

    public function validar()
    {
        $error = parent::validar();
        if ($error === '' && Operacion::aEntero($this->numero) < 1) {
            return 'Para Fibonacci debes pedir al menos 1 término.';
        }
        return $error;
    }

    public function calcularSerie()
    {
        $n = Operacion::aEntero($this->numero);
        $serie = [];
        $a = 0;
        $b = 1;
        for ($i = 0; $i < $n; $i++) {
            $serie[] = $a;
            $siguiente = $a + $b;
            $a = $b;
            $b = $siguiente;
        }
        return $serie;
    }
}

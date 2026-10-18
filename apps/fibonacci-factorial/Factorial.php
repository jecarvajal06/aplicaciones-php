<?php
require_once 'Operacion.php';

/**
 * Clase Factorial
 * Calcula n! y la serie 1!, 2!, ... n! (0! = 1).
 */
class Factorial extends Operacion
{
    const LIMITE = 20;

    public function getNombre()
    {
        return 'Factorial';
    }

    public function getLimite()
    {
        return self::LIMITE;
    }

    protected function mensajeLimite()
    {
        return 'Para el factorial el número debe estar entre 0 y ' . self::LIMITE
            . ': 21! ya no cabe en un entero de 64 bits de PHP.';
    }

    // Serie de factoriales: 1!, 2!, ..., n!  (si n = 0 solo 0! = 1)
    public function calcularSerie()
    {
        $n = Operacion::aEntero($this->numero);
        $serie = [];
        $acumulado = 1;
        if ($n === 0) {
            return [1];
        }
        for ($i = 1; $i <= $n; $i++) {
            $acumulado *= $i;
            $serie[] = $acumulado;
        }
        return $serie;
    }

    // Texto del cálculo, por ejemplo: 5! = 5 × 4 × 3 × 2 × 1 = 120
    public function getExpresion()
    {
        $n = Operacion::aEntero($this->numero);
        $serie = $this->calcularSerie();
        $resultado = end($serie);
        if ($n <= 1) {
            return "$n! = 1";
        }
        $factores = [];
        for ($i = $n; $i >= 1; $i--) {
            $factores[] = $i;
        }
        return "$n! = " . implode(' × ', $factores) . " = $resultado";
    }
}

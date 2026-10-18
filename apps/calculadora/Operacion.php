<?php
require_once 'Calculadora.php';

/**
 * Clase Operacion
 * Representa una entrada del historial: los dos números, la operación,
 * el resultado y la hora en que se hizo.
 */
class Operacion
{
    private $a;
    private $b;
    private $tipo;
    private $resultado;
    private $hora;

    public function __construct($a, $b, $tipo, $resultado, $hora = null)
    {
        $this->a = $a;
        $this->b = $b;
        $this->tipo = $tipo;
        $this->resultado = $resultado;
        $this->hora = $hora !== null ? $hora : date('H:i:s');
    }

    public function getHora()
    {
        return $this->hora;
    }

    // Texto de la operación, p. ej. "12.5 × 3 = 37.5" o "20% de 50 = 10"
    public function getTexto()
    {
        $a = Calculadora::formatear($this->a);
        $b = Calculadora::formatear($this->b);
        $r = Calculadora::formatear($this->resultado);
        $simbolo = Calculadora::SIMBOLOS[$this->tipo];
        if ($this->tipo === 'porcentaje') {
            return "$a% de $b = $r";
        }
        return "$a $simbolo $b = $r";
    }

    // Para guardar en la sesión como arreglo simple
    public function aArreglo()
    {
        return [$this->a, $this->b, $this->tipo, $this->resultado, $this->hora];
    }

    public static function desdeArreglo($datos)
    {
        return new Operacion($datos[0], $datos[1], $datos[2], $datos[3], $datos[4]);
    }
}

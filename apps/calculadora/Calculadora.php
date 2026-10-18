<?php
/**
 * Clase Calculadora
 * Realiza las operaciones básicas entre dos números (enteros o decimales).
 */
class Calculadora
{
    // Operaciones permitidas y su símbolo para mostrar
    const SIMBOLOS = [
        'suma' => '+',
        'resta' => '-',
        'multiplicacion' => '×',
        'division' => '÷',
        'porcentaje' => '% de',
    ];

    private $a;
    private $b;

    public function __construct($a, $b)
    {
        if (!is_numeric($a) || !is_numeric($b) || !is_finite((float)$a) || !is_finite((float)$b)) {
            throw new InvalidArgumentException('Ingresa dos números válidos.');
        }
        $this->a = (float)$a;
        $this->b = (float)$b;
    }

    public function sumar()
    {
        return $this->limpiar($this->a + $this->b);
    }

    public function restar()
    {
        return $this->limpiar($this->a - $this->b);
    }

    public function multiplicar()
    {
        return $this->limpiar($this->a * $this->b);
    }

    public function dividir()
    {
        if ($this->b == 0) {
            throw new InvalidArgumentException('No se puede dividir entre cero.');
        }
        return $this->limpiar($this->a / $this->b);
    }

    // A % de B = (A × B) / 100
    public function porcentaje()
    {
        return $this->limpiar(($this->a * $this->b) / 100);
    }

    // Ejecuta la operación indicada por su nombre
    public function calcular($operacion)
    {
        switch ($operacion) {
            case 'suma':           return $this->sumar();
            case 'resta':          return $this->restar();
            case 'multiplicacion': return $this->multiplicar();
            case 'division':       return $this->dividir();
            case 'porcentaje':     return $this->porcentaje();
        }
        throw new InvalidArgumentException('Operación no válida.');
    }

    // Redondea a 10 decimales para evitar errores de flotante (0.1 + 0.2 = 0.3)
    private function limpiar($valor)
    {
        $valor = round($valor, 10);
        return $valor == 0 ? 0.0 : $valor; // evita "-0"
    }

    // Convierte un número a texto sin ceros sobrantes ni notación científica
    public static function formatear($valor)
    {
        $texto = number_format(round((float)$valor, 10), 10, '.', '');
        $texto = rtrim(rtrim($texto, '0'), '.');
        return ($texto === '-0' || $texto === '') ? '0' : $texto;
    }
}

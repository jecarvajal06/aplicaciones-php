<?php
/**
 * Clase abstracta Operacion
 * Define lo que tienen en común Fibonacci y Factorial: un número de entrada,
 * un límite máximo y la forma de calcular una serie (cada hija la implementa a su manera).
 */
abstract class Operacion
{
    protected $numero;

    public function __construct($numero)
    {
        $this->numero = $numero;
    }

    public function getNumero()
    {
        return $this->numero;
    }

    // Nombre de la operación, por ejemplo "Fibonacci"
    abstract public function getNombre();

    // Mayor valor de n permitido (para no desbordar el entero de 64 bits)
    abstract public function getLimite();

    // Explica en una frase qué significa el límite
    abstract protected function mensajeLimite();

    // Devuelve la serie de números calculada
    abstract public function calcularSerie();

    /**
     * Valida el texto escrito en el formulario.
     * Devuelve un mensaje de error, o una cadena vacía si todo está bien.
     */
    public function validar()
    {
        return self::validarTexto($this->numero, $this);
    }

    // Convierte el texto ingresado a entero (solo se usa si validar() no dio error)
    public static function aEntero($texto)
    {
        return (int) trim($texto);
    }

    private static function validarTexto($texto, Operacion $operacion)
    {
        $texto = trim($texto);

        if ($texto === '') {
            return 'Debes ingresar un número.';
        }
        // Solo dígitos, con un signo menos opcional: rechaza texto y decimales
        if (!preg_match('/^-?\d+$/', $texto)) {
            return 'Debes ingresar un número entero (sin decimales ni letras).';
        }
        if ($texto[0] === '-') {
            return 'El número no puede ser negativo.';
        }
        // Se compara por longitud para no convertir números gigantes
        $sinCeros = ltrim($texto, '0');
        if (strlen($sinCeros) > 6 || (int) $texto > $operacion->getLimite()) {
            return $operacion->mensajeLimite();
        }
        return '';
    }
}

<?php
/**
 * Clase ConversorBinario
 * Convierte un número entero decimal a binario con divisiones sucesivas entre 2
 * (sin usar decbin ni base_convert).
 */
class ConversorBinario
{
    private $numero;      // valor entero ya validado
    private $negativo;    // true si el número ingresado es negativo
    private $pasos = [];  // filas del procedimiento: dividendo, cociente, residuo
    private $binario = '';

    /**
     * @param string|int $entrada Lo que escribió el usuario
     * @throws InvalidArgumentException si no es un entero válido
     */
    public function __construct($entrada)
    {
        $texto = trim((string) $entrada);

        if ($texto === '') {
            throw new InvalidArgumentException('Ingresa un número entero.');
        }
        if (!preg_match('/^([+-]?)(\d+)$/', $texto, $partes)) {
            throw new InvalidArgumentException('Solo se permiten números enteros (sin decimales ni letras).');
        }

        // Quitamos ceros a la izquierda y comparamos como texto con PHP_INT_MAX
        // para detectar números demasiado grandes antes de convertirlos a int.
        $digitos = ltrim($partes[2], '0');
        if ($digitos === '') {
            $digitos = '0';
        }
        $maximo = (string) PHP_INT_MAX;
        if (strlen($digitos) > strlen($maximo)
            || (strlen($digitos) === strlen($maximo) && strcmp($digitos, $maximo) > 0)) {
            throw new InvalidArgumentException('El número es demasiado grande (máximo ' . $maximo . ').');
        }

        $this->negativo = ($partes[1] === '-' && $digitos !== '0');
        $this->numero = (int) $digitos; // valor absoluto
    }

    public function esNegativo()
    {
        return $this->negativo;
    }

    /** Devuelve el binario como texto, con el signo menos si el número es negativo. */
    public function convertir()
    {
        $this->pasos = [];
        $dividendo = $this->numero;

        if ($dividendo === 0) {
            $this->binario = '0';
            return $this->binario;
        }

        $bits = '';
        while ($dividendo > 0) {
            $cociente = intdiv($dividendo, 2);
            $residuo = $dividendo % 2;
            $this->pasos[] = ['dividendo' => $dividendo, 'cociente' => $cociente, 'residuo' => $residuo];
            $bits = $residuo . $bits; // el último residuo queda primero
            $dividendo = $cociente;
        }

        $this->binario = ($this->negativo ? '-' : '') . $bits;
        return $this->binario;
    }

    /** Filas de la tabla del procedimiento (llamar después de convertir). */
    public function getPasos()
    {
        return $this->pasos;
    }

    /** Binario separado en grupos de 4 bits (rellena con ceros a la izquierda). */
    public function getAgrupado()
    {
        $signo = $this->negativo ? '-' : '';
        $bits = ltrim($this->binario, '-');
        $largo = strlen($bits);
        $relleno = (4 - $largo % 4) % 4;
        $bits = str_repeat('0', $relleno) . $bits;
        return $signo . implode(' ', str_split($bits, 4));
    }
}

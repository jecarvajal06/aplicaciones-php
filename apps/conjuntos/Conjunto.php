<?php
/**
 * Clase Conjunto
 * Representa un conjunto de números enteros (sin repetidos y ordenado).
 * Las operaciones (unión, intersección, diferencia) se hacen a mano con ciclos,
 * sin usar las funciones array_intersect, array_diff ni array_unique.
 */
class Conjunto
{
    private $elementos = [];   // enteros sin repetir, de menor a mayor
    private $repetidos = 0;    // cuántos repetidos se descartaron al crearlo

    // Recibe una lista de enteros (puede traer repetidos)
    public function __construct(array $numeros = [])
    {
        foreach ($numeros as $numero) {
            if ($this->contiene($numero)) {
                $this->repetidos++;      // ya estaba: se descarta
            } else {
                $this->insertar($numero);
            }
        }
    }

    /**
     * Crea un conjunto desde un texto como "1, 2; 3 4".
     * Lanza InvalidArgumentException si algún valor no es entero.
     */
    public static function desdeTexto($texto, $nombre)
    {
        $texto = trim((string) $texto);
        if ($texto === '') {
            throw new InvalidArgumentException("El conjunto $nombre está vacío: escribe al menos un número entero.");
        }

        // Los separadores pueden ser comas, punto y coma o espacios
        $partes = preg_split('/[\s,;]+/', $texto, -1, PREG_SPLIT_NO_EMPTY);
        $numeros = [];
        foreach ($partes as $parte) {
            // Entero con signo opcional; el "+ 0" detecta números demasiado grandes
            if (!preg_match('/^[+-]?\d+$/', $parte) || !is_int($parte + 0)) {
                throw new InvalidArgumentException("En el conjunto $nombre, \"$parte\" no es un número entero válido.");
            }
            $numeros[] = (int) $parte;
        }
        return new Conjunto($numeros);
    }

    // Inserta un número manteniendo el orden (como insertar una carta en la mano)
    private function insertar($numero)
    {
        $this->elementos[] = $numero;
        $i = count($this->elementos) - 1;
        while ($i > 0 && $this->elementos[$i - 1] > $numero) {
            $this->elementos[$i] = $this->elementos[$i - 1];
            $i--;
        }
        $this->elementos[$i] = $numero;
    }

    public function getElementos()
    {
        return $this->elementos;
    }

    public function getRepetidos()
    {
        return $this->repetidos;
    }

    public function estaVacio()
    {
        return count($this->elementos) === 0;
    }

    // Indica si el número pertenece al conjunto
    public function contiene($x)
    {
        foreach ($this->elementos as $elemento) {
            if ($elemento === $x) {
                return true;
            }
        }
        return false;
    }

    // A ∪ B: los elementos de A más los de B que no están en A
    public function union(Conjunto $otro)
    {
        $lista = $this->elementos;
        foreach ($otro->elementos as $elemento) {
            if (!$this->contiene($elemento)) {
                $lista[] = $elemento;
            }
        }
        return new Conjunto($lista);
    }

    // A ∩ B: los elementos de A que también están en B
    public function interseccion(Conjunto $otro)
    {
        $lista = [];
        foreach ($this->elementos as $elemento) {
            if ($otro->contiene($elemento)) {
                $lista[] = $elemento;
            }
        }
        return new Conjunto($lista);
    }

    // A − B: los elementos de A que no están en B
    public function diferencia(Conjunto $otro)
    {
        $lista = [];
        foreach ($this->elementos as $elemento) {
            if (!$otro->contiene($elemento)) {
                $lista[] = $elemento;
            }
        }
        return new Conjunto($lista);
    }

    // Notación de conjunto: {1, 2, 3}; si no tiene elementos, ∅
    public function mostrar()
    {
        if ($this->estaVacio()) {
            return '∅';
        }
        return '{' . implode(', ', $this->elementos) . '}';
    }

    public function __toString()
    {
        return $this->mostrar();
    }
}

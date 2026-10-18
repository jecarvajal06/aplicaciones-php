<?php
/**
 * Clase Estadistica
 * Recibe una serie de números reales y calcula promedio, mediana y moda.
 */
class Estadistica
{
    private $numeros;

    // $numeros: arreglo de números (int o float)
    public function __construct(array $numeros)
    {
        $this->numeros = array_map('floatval', array_values($numeros));
    }

    public function getNumeros()
    {
        return $this->numeros;
    }

    // Datos ordenados de menor a mayor
    public function ordenados()
    {
        $copia = $this->numeros;
        sort($copia);
        return $copia;
    }

    // Promedio = media aritmética: suma de los datos entre la cantidad
    public function promedio()
    {
        if (count($this->numeros) === 0) {
            return null;
        }
        return array_sum($this->numeros) / count($this->numeros);
    }

    // Mediana: valor central de los datos ordenados.
    // Si la cantidad es par, es el promedio de los dos centrales.
    public function mediana()
    {
        $n = count($this->numeros);
        if ($n === 0) {
            return null;
        }
        $orden = $this->ordenados();
        $mitad = intdiv($n, 2);
        if ($n % 2 === 1) {
            return $orden[$mitad];
        }
        return ($orden[$mitad - 1] + $orden[$mitad]) / 2;
    }

    // Tabla de frecuencias: arreglo de [valor, veces], ordenado por valor
    public function frecuencias()
    {
        $conteo = [];
        $valores = [];
        foreach ($this->numeros as $numero) {
            if ($numero == 0) {
                $numero = 0.0; // evita distinguir -0 de 0
            }
            $clave = 'v' . $numero; // prefijo para que la clave siempre sea texto
            if (!isset($conteo[$clave])) {
                $conteo[$clave] = 0;
                $valores[$clave] = $numero;
            }
            $conteo[$clave]++;
        }

        $tabla = [];
        foreach ($conteo as $clave => $veces) {
            $tabla[] = ['valor' => $valores[$clave], 'veces' => $veces];
        }
        usort($tabla, function ($a, $b) {
            return $a['valor'] <=> $b['valor'];
        });
        return $tabla;
    }

    // Moda: arreglo con todos los valores que más se repiten.
    // Si todos aparecen la misma cantidad de veces, no hay moda (arreglo vacío).
    public function moda()
    {
        $tabla = $this->frecuencias();
        if (count($tabla) === 0) {
            return [];
        }

        $maximo = 0;
        $minimo = PHP_INT_MAX;
        foreach ($tabla as $fila) {
            $maximo = max($maximo, $fila['veces']);
            $minimo = min($minimo, $fila['veces']);
        }
        if ($maximo === $minimo) {
            return [];
        }

        $moda = [];
        foreach ($tabla as $fila) {
            if ($fila['veces'] === $maximo) {
                $moda[] = $fila['valor'];
            }
        }
        return $moda;
    }

    // Presentación: máximo 4 decimales y sin ceros sobrantes
    public static function formatear($valor)
    {
        $texto = number_format(round($valor, 4), 4, '.', '');
        $texto = rtrim(rtrim($texto, '0'), '.');
        if ($texto === '-0' || $texto === '') {
            $texto = '0';
        }
        return $texto;
    }
}

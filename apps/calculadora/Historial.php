<?php
require_once 'Operacion.php';

/**
 * Clase Historial
 * Guarda las operaciones en $_SESSION (la sesión debe estar iniciada).
 */
class Historial
{
    const CLAVE = 'calculadora_historial';

    public function agregar(Operacion $operacion)
    {
        if (!isset($_SESSION[self::CLAVE])) {
            $_SESSION[self::CLAVE] = [];
        }
        $_SESSION[self::CLAVE][] = $operacion->aArreglo();
    }

    // Devuelve las operaciones, de la más reciente a la más antigua
    public function obtenerTodas()
    {
        $lista = [];
        if (isset($_SESSION[self::CLAVE])) {
            foreach (array_reverse($_SESSION[self::CLAVE]) as $datos) {
                $lista[] = Operacion::desdeArreglo($datos);
            }
        }
        return $lista;
    }

    public function borrar()
    {
        unset($_SESSION[self::CLAVE]);
    }
}

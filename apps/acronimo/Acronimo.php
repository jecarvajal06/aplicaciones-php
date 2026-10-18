<?php
/**
 * Clase Acronimo
 * Convierte una frase en su acrónimo (Portable Network Graphics -> PNG).
 * Los guiones separan palabras igual que los espacios; el resto de signos
 * de puntuación se eliminan (por eso "It's" queda como "Its" y da una sola I).
 */
class Acronimo
{
    private $frase;

    public function __construct($frase)
    {
        $this->frase = trim((string) $frase);
    }

    // Devuelve la lista de palabras detectadas, ya sin signos
    public function getPalabras()
    {
        // 1. Los guiones se tratan como espacios
        $texto = str_replace('-', ' ', $this->frase);

        // 2. Se eliminan los signos: solo quedan letras, números y espacios
        $texto = preg_replace('/[^\p{L}\p{N}\s]/u', '', $texto);

        // 3. Se separa por uno o más espacios (así no salen palabras vacías)
        $texto = trim($texto);
        if ($texto === '') {
            return [];
        }
        return preg_split('/\s+/u', $texto);
    }

    // Devuelve el acrónimo en mayúsculas (lanza excepción si no hay letras)
    public function getAcronimo()
    {
        if (!preg_match('/\p{L}/u', $this->frase)) {
            throw new InvalidArgumentException('Escribe una frase que contenga al menos una letra.');
        }

        $acronimo = '';
        foreach ($this->getPalabras() as $palabra) {
            $acronimo .= mb_strtoupper(mb_substr($palabra, 0, 1, 'UTF-8'), 'UTF-8');
        }
        return $acronimo;
    }
}

<?php
/**
 * Clase Pagina
 * Arma la estructura común de todas las páginas (encabezado, menú y pie)
 * para no repetir el mismo HTML en cada aplicación.
 */
class Pagina
{
    // Tema visual activo. Para cambiar el estilo de todo el sitio basta con
    // cambiar este valor por el nombre de otro archivo de css/temas/
    const TEMA = 'cuaderno';

    private $titulo;
    private $raiz; // ruta relativa hasta la carpeta principal del proyecto
    private $estiloPropio; // hoja de estilos opcional de cada aplicación

    // Lista de aplicaciones que aparecen en el menú
    private $aplicaciones = [
        ['carpeta' => 'acronimo',            'nombre' => 'Acrónimos',            'descripcion' => 'Convierte una frase en su acrónimo.'],
        ['carpeta' => 'fibonacci-factorial', 'nombre' => 'Fibonacci y factorial', 'descripcion' => 'Genera la serie de Fibonacci o el factorial de un número.'],
        ['carpeta' => 'estadistica',         'nombre' => 'Promedio, mediana y moda', 'descripcion' => 'Calcula medidas de una serie de números reales.'],
        ['carpeta' => 'conjuntos',           'nombre' => 'Conjuntos',            'descripcion' => 'Unión, intersección y diferencia de dos conjuntos.'],
        ['carpeta' => 'binario',             'nombre' => 'Decimal a binario',    'descripcion' => 'Convierte un número entero a binario.'],
        ['carpeta' => 'arbol-binario',       'nombre' => 'Árbol binario',        'descripcion' => 'Construye un árbol a partir de sus recorridos.'],
        ['carpeta' => 'calculadora',         'nombre' => 'Calculadora',          'descripcion' => 'Operaciones básicas con historial.'],
    ];

    public function __construct($titulo, $raiz = '', $estiloPropio = '')
    {
        $this->titulo = $titulo;
        $this->raiz = $raiz;
        $this->estiloPropio = $estiloPropio;
    }

    public function getAplicaciones()
    {
        return $this->aplicaciones;
    }

    public function getRaiz()
    {
        return $this->raiz;
    }

    // Imprime todo lo que va antes del contenido de la página
    public function encabezado()
    {
        $titulo = htmlspecialchars($this->titulo);
        $raiz = $this->raiz;
        $tema = self::TEMA;

        echo "<!DOCTYPE html>\n";
        echo "<html lang=\"es\">\n<head>\n";
        echo "    <meta charset=\"UTF-8\">\n";
        echo "    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\n";
        echo "    <title>$titulo</title>\n";
        echo "    <link rel=\"stylesheet\" href=\"{$raiz}css/temas/$tema.css\">\n";
        echo "    <link rel=\"stylesheet\" href=\"{$raiz}css/base.css\">\n";
        if ($this->estiloPropio !== '') {
            echo "    <link rel=\"stylesheet\" href=\"{$this->estiloPropio}\">\n";
        }
        echo "</head>\n<body>\n";
        $this->menu();
        echo "<main class=\"contenido\">\n";
        echo "    <h1>$titulo</h1>\n";
    }

    // Barra de navegación con enlaces a todas las aplicaciones
    private function menu()
    {
        echo "<header class=\"barra\">\n";
        echo "    <a class=\"barra-inicio\" href=\"{$this->raiz}index.php\">Aplicaciones PHP</a>\n";
        echo "    <nav class=\"barra-menu\">\n";
        foreach ($this->aplicaciones as $app) {
            $enlace = $this->raiz . 'apps/' . $app['carpeta'] . '/index.php';
            echo "        <a href=\"$enlace\">{$app['nombre']}</a>\n";
        }
        echo "    </nav>\n";
        echo "</header>\n";
    }

    // Imprime el cierre de la página
    public function pie()
    {
        echo "</main>\n";
        echo "<footer class=\"pie\">Taller de PHP y POO</footer>\n";
        echo "</body>\n</html>\n";
    }
}

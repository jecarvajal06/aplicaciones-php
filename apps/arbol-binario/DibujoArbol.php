<?php
require_once 'Nodo.php';

/**
 * Clase DibujoArbol
 * Convierte un árbol en listas HTML anidadas (se dibujan como organigrama con estilo.css).
 * Si un nodo tiene un solo hijo, el lado que falta se muestra como un hueco.
 */
class DibujoArbol
{
    public function generarHtml($raiz)
    {
        if ($raiz === null) {
            return '';
        }
        return "<div class=\"arbol\">\n<ul>\n" . $this->dibujarNodo($raiz) . "</ul>\n</div>\n";
    }

    private function dibujarNodo($nodo)
    {
        $valor = htmlspecialchars((string) $nodo->getValor());
        $html = "<li><span class=\"nodo\">$valor</span>";
        if (!$nodo->esHoja()) {
            $html .= "\n<ul>\n" . $this->dibujarHijo($nodo->getIzquierdo(), 'izquierdo')
                . $this->dibujarHijo($nodo->getDerecho(), 'derecho') . "</ul>\n";
        }
        return $html . "</li>\n";
    }

    private function dibujarHijo($hijo, $lado)
    {
        if ($hijo === null) {
            return "<li><span class=\"nodo hueco\" title=\"Sin hijo $lado\">&nbsp;</span></li>\n";
        }
        return $this->dibujarNodo($hijo);
    }
}

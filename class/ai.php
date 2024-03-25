<?php


class AI
{
    public function getNextMove(Tank $tank, Map $map)
    {
        // Lógica de la IA para determinar el siguiente movimiento válido
        // Podría utilizar algoritmos como A*, fuerza bruta, etc.
        // Asegurarse de que la posición devuelta sea válida en el mapa

        // Ejemplo simple: movimiento aleatorio válido
        do {
            $x = rand(0, $map->width - 1);
            $y = rand(0, $map->height - 1);
        } while (!$map->isValidPosition($x, $y));

        return ['x' => $x, 'y' => $y];
    }
}

?>



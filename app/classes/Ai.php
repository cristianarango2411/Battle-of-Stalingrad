<?php
class AI
{
    public function getNextMove(Tank $tank, Map $map, $targetX, $targetY)
    {
        $startX = $tank->getPosition()['x'];
        $startY = $tank->getPosition()['y'];

        $openList = [new Node($startX, $startY)];
        $closedList = [];

        $currentNode = null;
        while (!empty($openList)) {
            $currentNode = $this->getMinFNode($openList);
            if ($currentNode->x == $targetX && $currentNode->y == $targetY) {
                break; // We find the shortest path
            }

            array_splice($openList, array_search($currentNode, $openList), 1);
            $closedList[] = $currentNode;

            $neighbors = $this->getNeighbors($currentNode, $map);
            foreach ($neighbors as $neighbor) {
                if ($this->isInClosedList($neighbor, $closedList)) {
                    continue;
                }

                $existingNode = $this->findNode($neighbor, $openList);
                if ($existingNode) {
                    if ($neighbor->g < $existingNode->g) {
                        $existingNode->parent = $neighbor->parent;
                        $existingNode->g = $neighbor->g;
                        $existingNode->f = $existingNode->g + $existingNode->h;
                    }
                } else {
                    $neighbor->setHeuristic($targetX, $targetY);
                    $openList[] = $neighbor;
                }
            }
        }

        if ($currentNode && $currentNode->x == $targetX && $currentNode->y == $targetY) {
            $path = [];
            while ($currentNode) {
                $path[] = ['x' => $currentNode->x, 'y' => $currentNode->y];
                $currentNode = $currentNode->parent;
            }
            return array_reverse($path);
        }

        return []; // No way found
    }

    private function getMinFNode($openList)
    {
        $minFNode = null;
        foreach ($openList as $node) {
            if ($minFNode === null || $node->f < $minFNode->f) {
                $minFNode = $node;
            }
        }
        return $minFNode;
    }

    private function getNeighbors(Node $node, Map $map)
    {
        $neighbors = [];
        $offsets = [
            [-1, 0], [1, 0], [0, -1], [0, 1], // Horizontal and vertical movements
            [-1, -1], [-1, 1], [1, -1], [1, 1]// Diagonal movements
        ];

        foreach ($offsets as $offset) {
            $newX = $node->x + $offset[0];
            $newY = $node->y + $offset[1];

            if ($map->isValidPosition($newX, $newY)) {
                $neighbors[] = new Node($newX, $newY, $node);
            }
        }

        return $neighbors;
    }

    private function isInClosedList(Node $node, array $closedList)
    {
        foreach ($closedList as $closedNode) {
            if ($closedNode->x == $node->x && $closedNode->y == $node->y) {
                return true;
            }
        }
        return false;
    }

    private function findNode(Node $node, array $openList)
    {
        foreach ($openList as $openNode) {
            if ($openNode->x == $node->x && $openNode->y == $node->y) {
                return $openNode;
            }
        }
        return null;
    }
}

?>



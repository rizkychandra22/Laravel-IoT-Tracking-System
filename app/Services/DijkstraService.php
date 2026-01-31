<?php

namespace App\Services;

class DijkstraService
{
    public function shortestPath(array $graph, string $start, string $end)
    {
        $dist = [];
        $prev = [];
        $queue = [];

        foreach ($graph as $node => $edges) {
            $dist[$node] = INF;
            $prev[$node] = null;
        }

        $dist[$start] = 0;
        $queue = $dist;

        while (!empty($queue)) {
            $u = array_search(min($queue), $queue);
            unset($queue[$u]);

            if ($u === $end) break;

            foreach ($graph[$u] as $neighbor => $cost) {
                $alt = $dist[$u] + $cost;
                if ($alt < $dist[$neighbor]) {
                    $dist[$neighbor] = $alt;
                    $prev[$neighbor] = $u;
                    $queue[$neighbor] = $alt;
                }
            }
        }

        $path = [];
        for ($v = $end; $v !== null; $v = $prev[$v]) {
            array_unshift($path, $v);
        }

        return $path;
    }
}

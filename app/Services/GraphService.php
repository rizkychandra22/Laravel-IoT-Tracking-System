<?php

namespace App\Services;

class GraphService
{
    public function getGraph()
    {
        return [
            'A' => ['B' => 4, 'C' => 2],
            'B' => ['A' => 4, 'C' => 1, 'D' => 5],
            'C' => ['A' => 2, 'B' => 1, 'D' => 8],
            'D' => ['B' => 5, 'C' => 8],
        ];
    }

    public function getCoordinates()
    {
        return [
            'A' => [-6.9741, 106.8246],
            'B' => [-6.9750, 106.8252],
            'C' => [-6.9738, 106.8239],
            'D' => [-6.9760, 106.8260],
        ];
    }
}

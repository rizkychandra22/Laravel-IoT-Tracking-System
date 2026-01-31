<?php

namespace App\Services;

class GraphService
{
    public function getGraph()
    {
        return [
            'rumah' => [
                'kampus_ummi' => 4,
                'secapa_polri' => 2
            ],
            'kampus_ummi' => [
                'rumah' => 4,
                'secapa_polri' => 1,
                'cibadak' => 6
            ],
            'secapa_polri' => [
                'rumah' => 2,
                'kampus_ummi' => 1,
                'cibadak' => 5
            ],
            'cibadak' => [
                'kampus_ummi' => 6,
                'secapa_polri' => 5
            ]
        ];
    }

    public function getCoordinates()
    {
        return [
            'rumah' => [-6.9083592252156905, 106.89633620913852],
            'kampus_ummi' => [-6.9185548295720265, 106.93409279379514],
            'secapa_polri' => [-6.911272035193913, 106.92435462263086],
            'cibadak' => [-6.890065918562608, 106.78160176597994],
        ];
    }

}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PortfolioCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->transform(function($portfolio) {
                return new PortfolioResource($portfolio);
            }),
            'meta' => [
                'total' => $this->total(),
                'count' => $this->count(),
                'per_page' => $this->perPage(),
                'current_page' => $this->currentPage(),
                'total_pages' => $this->lastPage(),
            ],
            'filters' => [
                'categories' => ['wedding', 'portrait', 'event', 'commercial', 'fashion'],
                'sort_options' => [
                    'latest' => 'Terbaru',
                    'popular' => 'Terpopuler', 
                    'most_viewed' => 'Paling Dilihat'
                ]
            ]
        ];
    }
}
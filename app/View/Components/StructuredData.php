<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Services\StructuredDataService;

class StructuredData extends Component
{
    protected $service;
    public $schema;

    public function __construct(StructuredDataService $service, $type = 'home', $model = null)
    {
        $this->service = $service;

        $this->schema = match($type) {
            'home' => $service->getHomePageSchema(),
            'category' => $service->getCategorySchema($model),
            'post' => $service->getPostSchema($model),
            'contact' => $service->getContactPageSchema(),
            default => ''
        };
    }

    public function render()
    {
        return view('components.structured-data');
    }
}
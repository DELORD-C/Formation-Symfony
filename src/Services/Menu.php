<?php

namespace App\Services;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class Menu {
    private array $items = [
        'Home' => 'app_default_hello',
        'Post' => [
            'Create' => 'app_post_create',
            'All' => 'app_post_all'
        ],
        'Review' => [
            'Create' => 'app_review_create',
            'All' => 'app_review_all'
        ]
    ];

    public function __construct(
        private readonly UrlGeneratorInterface $router,
    ) {
        $this->generateRoutes();
    }

    public function generateRoutes(): void
    {
        foreach ($this->items as $key => $item) {
            if (is_array($item)) {
                foreach ($this->items[$key] as $subKey => $subItem) {
                    $this->items[$key][$subKey] = $this->router->generate($subItem);
                }
            }
            else {
                $this->items[$key] = $this->router->generate($item);
            }
        }
    }
    public function getMenu(): array
    {
        return $this->items;
    }
}
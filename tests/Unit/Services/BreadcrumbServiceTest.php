<?php

use App\Services\BreadcrumbService;

describe('BreadcrumbService', function () {
    
    test('adds breadcrumb item', function () {
        $service = new BreadcrumbService();
        
        $service->add('Home', '/');
        
        $items = $service->get();
        
        expect($items)->toHaveCount(1)
            ->and($items[0])->toHaveKey('title', 'Home')
            ->and($items[0])->toHaveKey('url', '/')
            ->and($items[0])->toHaveKey('active', false);
    });
    
    test('adds home link with default values', function () {
        $service = new BreadcrumbService();
        
        $service->addHome();
        
        $items = $service->get();
        
        expect($items)->toHaveCount(1)
            ->and($items[0]['title'])->toBe('Home');
    });
    
    test('adds current active breadcrumb', function () {
        $service = new BreadcrumbService();
        
        $service->add('Home', '/')
                ->addCurrent('About Us');
        
        $items = $service->get();
        
        expect($items)->toHaveCount(2)
            ->and($items[1]['active'])->toBeTrue()
            ->and($items[1]['url'])->toBeNull();
    });
    
    test('builds breadcrumb chain', function () {
        $service = new BreadcrumbService();
        
        $service->addHome()
                ->add('Articles', '/articles')
                ->add('Technology', '/articles/technology')
                ->addCurrent('Laravel Testing');
        
        expect($service->count())->toBe(4)
            ->and($service->isEmpty())->toBeFalse();
    });
    
    test('returns empty array when no items', function () {
        $service = new BreadcrumbService();
        
        expect($service->get())->toBeArray()->toBeEmpty()
            ->and($service->isEmpty())->toBeTrue()
            ->and($service->count())->toBe(0);
    });
    
    test('renders HTML breadcrumb', function () {
        $service = new BreadcrumbService();
        
        $service->add('Home', '/')
                ->addCurrent('About');
        
        $html = $service->render();
        
        expect($html)
            ->toContain('<nav')
            ->toContain('breadcrumb')
            ->toContain('<a href="/">Home</a>')
            ->toContain('<span>About</span>');
    });
    
    test('returns empty string when rendering empty breadcrumb', function () {
        $service = new BreadcrumbService();
        
        expect($service->render())->toBe('');
    });
    
    test('renders JSON-LD structured data', function () {
        $service = new BreadcrumbService();
        
        $service->add('Home', 'https://example.com')
                ->add('Articles', 'https://example.com/articles')
                ->addCurrent('Test Article');
        
        $json = $service->renderJson();
        $data = json_decode($json, true);
        
        expect($data)->toHaveKey('@context', 'https://schema.org')
            ->and($data)->toHaveKey('@type', 'BreadcrumbList')
            ->and($data)->toHaveKey('itemListElement')
            ->and($data['itemListElement'])->toHaveCount(3)
            ->and($data['itemListElement'][0]['position'])->toBe(1)
            ->and($data['itemListElement'][2]['position'])->toBe(3);
    });
});

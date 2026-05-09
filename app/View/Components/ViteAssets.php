<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class ViteAssets extends Component
{
    /**
     * Get the manifest data from the built Vite assets.
     */
    private function getManifest(): ?array
    {
        $manifestPath = public_path('build/manifest.json');
        
        if (!file_exists($manifestPath)) {
            return null;
        }
        
        return json_decode(file_get_contents($manifestPath), true);
    }

    /**
     * Get CSS asset URL from manifest.
     */
    public function getCssUrl(): ?string
    {
        $manifest = $this->getManifest();
        
        if (!$manifest || !isset($manifest['resources/css/app.css'])) {
            return null;
        }
        
        return '/build/' . $manifest['resources/css/app.css']['file'];
    }

    /**
     * Get JS asset URL from manifest.
     */
    public function getJsUrl(): ?string
    {
        $manifest = $this->getManifest();
        
        if (!$manifest || !isset($manifest['resources/js/app.js'])) {
            return null;
        }
        
        return '/build/' . $manifest['resources/js/app.js']['file'];
    }

    /**
     * Render the component.
     */
    public function render(): View
    {
        return view('components.vite-assets', [
            'cssUrl' => $this->getCssUrl(),
            'jsUrl' => $this->getJsUrl(),
        ]);
    }
}

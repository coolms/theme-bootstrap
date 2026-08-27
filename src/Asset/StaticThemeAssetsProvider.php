<?php

declare(strict_types=1);

namespace CoolMS\ThemeBootstrap\Asset;

use App\Theme\Domain\Service\ThemeAssetsProviderInterface;
use App\Theme\Domain\ValueObject\ThemeAssets;

/**
 * Serves Bootstrap 5 assets from CDN.
 * No build step required -- drop-in for any theme that extends coolms-bootstrap.
 */
final class StaticThemeAssetsProvider implements ThemeAssetsProviderInterface
{
    private const string BOOTSTRAP_CSS = 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css';

    private const string BOOTSTRAP_JS = 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js';

    public string $slug { get => 'coolms-bootstrap'; }

    public function getAssets(string $assetsPath, string $assetsUrl): ThemeAssets
    {
        return new ThemeAssets(
            css: [['url' => self::BOOTSTRAP_CSS]],
            js: [['url' => self::BOOTSTRAP_JS]],
        );
    }
}

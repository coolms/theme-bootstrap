<?php

declare(strict_types=1);

namespace CoolMS\ThemeBootstrap\Provider;

use CoolMS\Core\Theme\ThemeProviderInterface;

/**
 * Registers coolms-bootstrap as a known theme.
 * Used as a parent theme -- not intended to be activated directly.
 */
final class ThemeBootstrapProvider implements ThemeProviderInterface
{
    public string $slug { get => 'coolms-bootstrap'; }
    public string $themePath { get => $this->bundleRoot . '/templates'; }
    public string $manifestPath { get => $this->bundleRoot . '/theme.yaml'; }
    public string $assetsPath { get => ''; }
    public string $assetsUrl { get => ''; }
    private readonly string $bundleRoot;

    public function __construct()
    {
        $this->bundleRoot = dirname(__DIR__, 2);
    }
}

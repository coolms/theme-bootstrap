<?php

declare(strict_types=1);

namespace CoolMS\ThemeBootstrap\Provider;

use CoolMS\Core\Theme\ThemeProviderInterface;

/**
 * Registers coolms-bootstrap as a known theme.
 * Used as a parent theme -- not intended to be activated directly.
 *
 * !! IT HAS ASSETS NOW, AND THAT IS WHY THE TWO PATHS ARE NO LONGER EMPTY.
 * They were `''` because the theme owned no files: Bootstrap came from
 * `cdn.jsdelivr.net`, so there was nothing to publish and nowhere to publish
 * it from. Self-hosting Bootstrap gives the theme a `public/`, and both paths
 * have to be declared for `InstallThemeCommand` to find it and for
 * `coolms:theme:publish coolms-bootstrap --assets` to copy it into
 * `public/themes/coolms-bootstrap/`, which is what nginx actually serves.
 *
 * A PARENT theme needs its own assets published in its own right: the publish
 * command copies ONE theme's `assetsPath`, it does not walk the chain, and the
 * resolver hands each chain entry its own `assetsUrl`. Publishing the child
 * does not bring the parent's files with it.
 */
final class ThemeBootstrapProvider implements ThemeProviderInterface
{
    public string $slug { get => 'coolms-bootstrap'; }
    public string $themePath { get => $this->bundleRoot . '/templates'; }
    public string $manifestPath { get => $this->bundleRoot . '/theme.yaml'; }
    public string $assetsPath { get => $this->bundleRoot . '/public'; }
    public string $assetsUrl { get => '/themes/coolms-bootstrap'; }
    private readonly string $bundleRoot;

    public function __construct()
    {
        $this->bundleRoot = dirname(__DIR__, 2);
    }
}

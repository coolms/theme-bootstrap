<?php

declare(strict_types=1);

namespace CoolMS\ThemeBootstrap;

use CoolMS\ThemeBootstrap\DependencyInjection\ThemeBootstrapExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Symfony bundle for the CoolMS Bootstrap 5 Theme overlay.
 *
 * Registers a FilesystemTemplateLoader at priority 25 so that
 * templates/forms/ overrides Identity module fallbacks.
 *
 * The ThemeBootstrapExtension implements PrependExtensionInterface to append
 * its config/modules/ directory to coolms.form.scan_directories.
 */
final class ThemeBootstrapBundle extends Bundle
{
    public function getPath(): string
    {
        // __DIR__ = .../theme-bootstrap/src
        // dirname(__DIR__) = .../theme-bootstrap (package root)
        return dirname(__DIR__);
    }

    public function getContainerExtension(): ExtensionInterface
    {
        return new ThemeBootstrapExtension();
    }
}

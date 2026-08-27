<?php

declare(strict_types=1);

namespace CoolMS\ThemeBootstrap\DependencyInjection;

use CoolMS\Dtmpl\Loader\FilesystemTemplateLoader;
use CoolMS\ThemeBootstrap\Asset\StaticThemeAssetsProvider;
use CoolMS\ThemeBootstrap\Provider\ThemeBootstrapProvider;
use LogicException;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;

/**
 * DI Extension for the CoolMS Bootstrap 5 Theme bundle.
 *
 * Registers a FilesystemTemplateLoader at priority 25 pointing to
 * packages/theme-bootstrap/templates/. At this priority it wins over the
 * Identity module loader (priority 5) and the default FS loader (priority 0),
 * so Bootstrap-styled form templates override Identity semantic fallbacks.
 *
 * Implements PrependExtensionInterface to append the theme config/modules/
 * directory to coolms.form.scan_directories — scanned after Identity base
 * configs, so form_type component YAMLs are registered into FormConfigRegistry.
 */
final class ThemeBootstrapExtension extends Extension implements PrependExtensionInterface
{
    public function prepend(ContainerBuilder $container): void
    {
        // Append our config/modules directory to be scanned for form_type configs.
        $dirs = $container->hasParameter('coolms.form.scan_directories')
            ? $container->getParameter('coolms.form.scan_directories')
            : ['%kernel.project_dir%/config/modules'];
        // getParameter() returns any container-parameter type. This seam is a
        // list of directories, so anything else is a misconfiguration -- say so
        // rather than appending to a string and failing somewhere else later.
        if (!is_array($dirs)) {
            throw new LogicException('coolms.form.scan_directories must be an array of directories.');
        }
        $dirs[] = dirname(__DIR__, 2) . '/config/modules';
        $container->setParameter('coolms.form.scan_directories', $dirs);
    }

    public function load(array $configs, ContainerBuilder $container): void
    {
        // Template loader — Bootstrap templates override Identity module fallbacks.
        // dirname(__DIR__, 2) = .../theme-bootstrap (package root)
        $container->register('dtmpl.module_loader.theme_bootstrap', FilesystemTemplateLoader::class)
            ->setArgument('$basePath', dirname(__DIR__, 2) . '/templates')
            ->addTag('dtmpl.template_loader', ['priority' => 25])
            ->setAutowired(false)
            ->setPublic(false);

        $container->register(ThemeBootstrapProvider::class)
            ->setAutowired(false)
            ->setAutoconfigured(true)
            ->setPublic(false);

        $container->register(StaticThemeAssetsProvider::class)
            ->setAutowired(false)
            ->setAutoconfigured(true)
            ->setPublic(false);
    }

    public function getAlias(): string
    {
        return 'coolms_theme_bootstrap';
    }
}

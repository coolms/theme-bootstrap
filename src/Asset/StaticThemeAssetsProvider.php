<?php

declare(strict_types=1);

namespace CoolMS\ThemeBootstrap\Asset;

use CoolMS\Core\Theme\ThemeAssets;
use CoolMS\Core\Theme\ThemeAssetsProviderInterface;

/**
 * Serves Bootstrap 5 from the theme's own `public/`, not from a CDN.
 * No build step required -- drop-in for any theme that extends coolms-bootstrap.
 *
 * !! THE MOVE OFF `cdn.jsdelivr.net` IS A PRIVACY FIX, NOT A PERFORMANCE ONE.
 * A stylesheet and a script fetched from a third party hand that third party
 * every visitor's IP address, user agent and referring page, on every view,
 * before the visitor has consented to anything. In the EU that is a transfer
 * to a third country with no legal basis -- LG Muenchen I awarded damages for
 * exactly this shape in January 2022 (Google Fonts, same mechanism).
 *
 * !! AND THIS THEME IS SHIPPED AS A PRODUCT, which is what makes it worth
 * fixing here rather than in one site. `theme-coolms-site` extends this
 * theme, and so does `theme-default`; both are published and installable, so
 * every install inherited the transfer without being told. A defect a user
 * cannot see, in a package they did not write, is the kind that has to be
 * fixed at the source.
 *
 * !! AND IT CANNOT BE FIXED ANYWHERE ELSE. A child theme cannot suppress this,
 * because `ThemeAssets` is a plain `list<array{url: string}>` -- no key, no
 * dedup -- and `ThemeChainAssetsResolver` walks the chain parent-first and
 * appends. A child can ADD an asset; nothing lets it remove one. So the only
 * routes were changing this file or ceasing to extend the theme.
 *
 * The files are byte-identical to the Bootstrap 5.3.3 dist, verified against
 * three independent origins (jsdelivr, unpkg, and the GitHub release zip) --
 * identical sha256 on all four, including the source maps, which are shipped
 * so the `sourceMappingURL` comments at the end of both minified files still
 * resolve. MIT licence in `public/LICENSE-bootstrap.txt`.
 *
 * !! Built from `$assetsUrl` rather than a literal `/themes/coolms-bootstrap`:
 * the resolver passes each chain entry its OWN assets URL, and a hardcoded
 * path would be a second copy of a fact the caller already holds.
 */
final class StaticThemeAssetsProvider implements ThemeAssetsProviderInterface
{
    private const string BOOTSTRAP_CSS = '/css/bootstrap.min.css';

    private const string BOOTSTRAP_JS = '/js/bootstrap.bundle.min.js';

    public string $slug { get => 'coolms-bootstrap'; }

    public function getAssets(string $assetsPath, string $assetsUrl): ThemeAssets
    {
        $base = rtrim($assetsUrl, '/');

        return new ThemeAssets(
            css: [['url' => $base . self::BOOTSTRAP_CSS]],
            js: [['url' => $base . self::BOOTSTRAP_JS]],
        );
    }
}

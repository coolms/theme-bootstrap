# coolms/theme-bootstrap

[![CI](https://github.com/coolms/theme-bootstrap/actions/workflows/ci.yml/badge.svg)](https://github.com/coolms/theme-bootstrap/actions/workflows/ci.yml)
[![PHP](https://img.shields.io/badge/php-%E2%89%A5%208.5-777bb4)](https://www.php.net/releases/8.5/en.php)
[![License](https://img.shields.io/badge/license-MIT-green)](LICENSE)

**Bootstrap theme overlay for CoolMS -- adds Bootstrap classes to the base form and nav templates**

A theme is not a module. It owns no domain and no storage: it implements a small
provider port, ships templates and assets, and wires them into the container.
That is why this package extends Symfony's `Bundle` rather than
`AbstractCoolmsBundle`, and why it requires `coolms/core` -- the contract -- and
not `coolms/coolms`, which is a deployment.

## Installation

```bash
composer require coolms/theme-bootstrap
```

```php
// config/bundles.php
CoolMS\ThemeBootstrap\ThemeBootstrapBundle::class => ['all' => true],
```

```bash
bin/console coolms:theme:install coolms-bootstrap
bin/console coolms:theme:publish coolms-bootstrap --assets
bin/console coolms:theme:activate coolms-bootstrap
```

The publish step is not optional and not cosmetic: Bootstrap is served from
this package, and `public/themes/coolms-bootstrap/` -- the directory your web
server actually reads -- is a COPY that the publish command makes. Skip it and
every page renders unstyled with two 404s.

The same applies to a theme that merely EXTENDS this one. `coolms:theme:publish`
copies one theme's assets; it does not walk the inheritance chain. Publishing
`your-theme` does not publish the Bootstrap underneath it.

## Assets

Bootstrap 5.3.3 ships inside this package, in `public/`, and is served from
your own domain.

| file | bytes |
|---|---|
| `public/css/bootstrap.min.css` | 232,757 |
| `public/js/bootstrap.bundle.min.js` | 80,672 |
| `public/LICENSE-bootstrap.txt` | MIT |

**It used to come from `cdn.jsdelivr.net`, and that was a defect rather than a
convenience.** A stylesheet and a script fetched from a third party hand that
third party every visitor's IP address, user agent and referring page, on every
page view, before the visitor has consented to anything. For an operator in the
EU that is a transfer with no legal basis; LG Muenchen I awarded damages for
exactly this shape in January 2022, over Google Fonts, which is the same
mechanism with a different host. Self-hosting removes the transfer instead of
disclosing it, and a theme installed by other people should not make that
decision on their behalf.

The two files are the official Bootstrap 5.3.3 dist with one line removed from
each: the trailing `sourceMappingURL` comment. **What remains is a byte-exact
PREFIX of upstream** -- 232,757 of 232,803 bytes for the CSS, 80,672 of 80,721
for the JS -- so the original verification still holds and is still checkable:
take the upstream file, cut it at that length, and the digests match. Upstream
was verified against three independent origins (jsdelivr, unpkg, and the GitHub
release archive) with identical SHA-256 before anything was removed, and that
version is in this repository's history.

**No source maps ship, and the `sourceMappingURL` comments are removed with
them.** A `.map` carries `sourcesContent` -- the original SCSS and JS verbatim,
comments included -- and publishing one puts 900 KB of upstream source into
every install to serve a devtools convenience almost nobody uses on a
dependency. Removing the comments as well is what stops a consumer's browser
requesting a file that is not there. Anyone debugging Bootstrap itself should
fetch the maps from upstream, where they are maintained.

To move to a newer Bootstrap, replace the two files, strip the trailing
`sourceMappingURL` line from each, update the sizes and the version named here,
and re-run the publish step. Nothing else references the version.

## What it is

| | |
|---|---|
| slug | `coolms-bootstrap` |
| front-end stack | `ssr` |
| manifest | `theme.yaml` |

## Contracts it implements

- `CoolMS\Core\Theme\ThemeAssetsProviderInterface`
- `CoolMS\Core\Theme\ThemeProviderInterface`

Both live in `coolms/core`. Before that they were application classes, which
is what made a theme unpublishable.

## Branches

`develop` is the default and where work lands; `main` carries releases. The
package is not tagged yet -- the platform generation ships as `2.0.0-alpha.N`
first, and the lockstep set is tagged together.

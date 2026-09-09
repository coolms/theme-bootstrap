# Changelog

All notable changes to `coolms/theme-bootstrap` are recorded here.

The format follows [Keep a Changelog](https://keepachangelog.com/en/1.1.0/).
Versioning is described in `CONTRIBUTING.md` -- read it before assuming what a
major number means here.

## Unreleased

### Changed: Bootstrap is served from this package, not from `cdn.jsdelivr.net`

!! **THIS NEEDS AN EXTRA INSTALL STEP. Run
`bin/console coolms:theme:publish coolms-bootstrap --assets`.** Without it the
CSS and JS 404 and every page renders unstyled. `public/themes/<slug>/` is a
COPY the publish command makes, and until this release this theme had nothing
to copy. The step is now in the README's install block.

The same applies to a theme that merely EXTENDS this one: publish copies ONE
theme's assets and does not walk the inheritance chain, so publishing your own
theme does not publish the Bootstrap underneath it.

**Why it changed.** A stylesheet and a script fetched from a third party hand
that third party every visitor's IP address, user agent and referring page, on
every page view, before the visitor has consented to anything. For an operator
in the EU that is a transfer with no legal basis -- LG Muenchen I awarded
damages for exactly this shape in January 2022, over Google Fonts, which is
the same mechanism with a different host. Someone installing a CMS theme is
not making a decision about sending their visitors to a CDN; they were never
asked.

**Why here and not in a child theme.** A child cannot suppress it.
`ThemeAssets` is a plain `list<array{url: string}>` -- no key, no dedup -- and
`ThemeChainAssetsResolver` walks the chain parent-first and appends, so a child
theme can ADD an asset and never remove one.

Two files ship, not four. The two files are the official Bootstrap 5.3.3 dist with one line removed from
each: the trailing `sourceMappingURL` comment. **What remains is a byte-exact
PREFIX of upstream** -- 232,757 of 232,803 bytes for the CSS, 80,672 of 80,721
for the JS -- so the original verification still holds and is still checkable:
take the upstream file, cut it at that length, and the digests match. Upstream
was verified against three independent origins (jsdelivr, unpkg, and the GitHub
release archive) with identical SHA-256 before anything was removed, and that
version is in this repository's history. MIT licence in
`public/LICENSE-bootstrap.txt`.

!! **NO SOURCE MAPS, AND THE `sourceMappingURL` COMMENTS GO WITH THEM.** They
were shipped at first so those comments resolved. A `.map` carries
`sourcesContent` -- the original source verbatim, comments included -- so
publishing one puts 900 KB of upstream source into every install for a devtools
convenience almost nobody uses on a dependency, and the publish guard blocks it
outright with a rule that has no waiver key. Removing the comments as well is
what stops a consumer's browser requesting a file that is not there. Anyone
debugging Bootstrap itself should fetch the maps from upstream.

`ThemeBootstrapProvider::$assetsPath` and `$assetsUrl` are no longer empty
strings -- they were correct while the theme owned no files and wrong the
moment it does.

## 2.0.0-alpha3 - 2026-09-03

### Fixed

**Declares `symfony/config`, without which this package cannot be loaded at
all.** The bundle class extends `Symfony\Component\HttpKernel\Bundle\Bundle`,
which extends `DependencyInjection\Kernel\AbstractBundle`, which implements
`Config\Definition\ConfigurableInterface` -- and `symfony/dependency-injection`
carries `symfony/config` in **require-dev**, not `require`. So installing
this package on its own produced:

```
Interface "Symfony\Component\Config\Definition\ConfigurableInterface" not found
```

!! **Invisible in any application that installs `symfony/framework-bundle`**,
which pulls `symfony/config` in transitively -- which is every application
this theme had ever been installed into. Found by resolving the package from
its tag into an empty tree and then checking that every `use` statement in
its own `src/` resolves against what that install produced. Installing is not
the check: 2.0.0-alpha2 installed perfectly and could not load its own bundle.

Same class of defect as `coolms/core-bundle` requiring
`symfony/translation-contracts` without `symfony/translation`: a dependency
the host application had been supplying that the manifest never declared.
## 2.0.0-alpha2 - 2026-09-03

**First published release.** Nothing before this was ever released, so there
is no earlier history to describe.

**A pre-release. It carries no compatibility promise**, which is the honest
statement of where the platform is: the shape is still moving, and a stable
tag would be a promise that cannot be kept yet.

Composer will not install it under default stability. Set

```json
"minimum-stability": "alpha",
"prefer-stable": true
```

in your root `composer.json`, then:

```
composer require coolms/theme-bootstrap:^2.0
```

### What it is

A **structural base**, not a finished look: **33 DTMPL templates** (forms,
navigation, layouts, error pages) with Bootstrap 5 classes on them, 15 config
files, and 4 PHP classes -- a bundle, its extension, and the manifest glue.

!! **It ships no `pages/` templates, so it is meant to be extended rather
than assigned.** Assigning it directly to a site takes the public front end
to a 500: *fallback template `pages/page.html.dtmpl` not found*. Use
`coolms/theme-default`, or a theme of your own with `extends:
coolms-bootstrap` in its `theme.yaml`.

### Version

It starts at 2.0.0 rather than 1.0.0. The theme requires `coolms/core`,
which puts it in the platform's lockstep set by the release policy's own
classifier, and lockstep members share a major. A 1.x theme beside 2.x
siblings would invite the question of whether it had missed a release.

# Changelog

All notable changes to `coolms/theme-bootstrap` are recorded here.

The format follows [Keep a Changelog](https://keepachangelog.com/en/1.1.0/).
Versioning is described in `CONTRIBUTING.md` -- read it before assuming what a
major number means here.
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

⚠️ **It ships no `pages/` templates, so it is meant to be extended rather
than assigned.** Assigning it directly to a site takes the public front end
to a 500: *fallback template `pages/page.html.dtmpl` not found*. Use
`coolms/theme-default`, or a theme of your own with `extends:
coolms-bootstrap` in its `theme.yaml`.

### Version

It starts at 2.0.0 rather than 1.0.0. The theme requires `coolms/core`,
which puts it in the platform's lockstep set by the release policy's own
classifier, and lockstep members share a major. A 1.x theme beside 2.x
siblings would invite the question of whether it had missed a release.

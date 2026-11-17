# Contributing to Lite Slider

Thanks for helping to improve Lite Slider. This guide explains how to set up, what to check, and how changes get merged.

Using an AI tool? Point it to [AGENTS.md](AGENTS.md).

## Development setup

1. Set up a ConcreteCMS 9.0+ site (PHP 7.4 to 8.4).
2. Clone your fork into the site's `packages` folder:

   ```
   git clone https://github.com/<you>/xw_lite_slider.git packages/xw_lite_slider
   ```

3. Install the package from Dashboard, Extend ConcreteCMS.

## Where the CSS and JS come from

- `css/lite-slider.css`, `css/lite-slider-theme.css`, `blocks/xw_lite_slider/auto.css`, `js/lite-slider.js` and `js/glide.js` are generated from `build/`. Do not edit them by hand. Rebuild them with:

  ```
  cd build
  npm ci
  npm run production
  ```

  Use the Node version in `build/.nvmrc` (Node 20+).
  The asset build check is not enforced in CI yet; mention in your PR if you rebuilt the assets.
- `vendor/xanweb/ls-v-item-list/js/v-item-list.js` is a prebuilt bundle. Do not change it.
- `vendor/xanweb/ls-v-item-list/css/v-item-list.css` and `vendor/xanweb/ls-html-helpers/css/dashboard/ios-toggle-button.css` are edited directly.

## No short echo tags

Do not use `<?=` in any PHP file. The ConcreteCMS marketplace review rejects it, and CI fails on it. Use `<?php echo ...; ?>` instead.

## Pull requests

1. Fork the repository and create a branch.
2. Open a pull request against `main`.
3. CI must pass.
4. A Xanweb maintainer reviews it.
5. Pull requests are merged with squash merge.

## Changelog

Maintainers curate `CHANGELOG.md`. For a user-visible change you may add a line under `Unreleased`.

## Running the checks locally

From the repository root (Python 3.8+):

```
python -m unittest discover -s .github/scripts/tests
python .github/scripts/export_package.py HEAD dist/candidate.zip
python .github/scripts/check_package.py dist/candidate.zip
```

The last two commands export the release zip and check its contents.

## Licensing

By contributing, you agree that your contributions are licensed under the MIT License of this project, and that Xanweb may reuse them in its other products.

"""Check the contents of an exported release zip."""
import pathlib
import sys
import zipfile

TOP = "xw_lite_slider/"

MUST_SHIP_FILES = [
    "controller.php",
    "icon.png",
    "LICENSE",
    "README.md",
    "NOTICE",
    "CHANGELOG.md",
    "blocks/xw_lite_slider/controller.php",
    "blocks/xw_lite_slider/db.xml",
    "blocks/xw_lite_slider/view.php",
    "blocks/xw_lite_slider/add.php",
    "blocks/xw_lite_slider/edit.php",
    "blocks/xw_lite_slider/auto.css",
    "blocks/xw_lite_slider/icon.png",
    "css/lite-slider.css",
    "css/lite-slider-theme.css",
    "js/glide.js",
    "js/glide.js.license.txt",
    "js/lite-slider.js",
    "vendor/xanweb/ls-v-item-list/js/v-item-list.js",
    "vendor/xanweb/ls-v-item-list/css/v-item-list.css",
    "vendor/xanweb/ls-html-helpers/css/dashboard/ios-toggle-button.css",
]

MUST_SHIP_NONEMPTY_DIRS = [
    "blocks/xw_lite_slider/form/",
    "src/Xanweb/LiteSlider/",
] + ["vendor/xanweb/ls-%s/" % n for n in (
    "block", "common", "html-helpers", "js-localization", "module",
    "request", "v-item-list",
)]

ALLOWED_TOP_LEVEL = {
    "controller.php", "icon.png", "LICENSE", "README.md", "NOTICE",
    "CHANGELOG.md", "blocks", "css", "js", "src", "vendor",
}

DEV_ONLY = [
    "build/", ".github/", ".gitattributes", ".gitignore", ".editorconfig",
    "CONTRIBUTING.md", "CODE_OF_CONDUCT.md", "SECURITY.md", "AGENTS.md", "CLAUDE.md",
]


def _is_dev_only(rel):
    for d in DEV_ONLY:
        if d.endswith("/"):
            if rel.startswith(d):
                return True
        elif rel == d:
            return True
    return False


def check(zip_path, extra_required=()):
    problems = []
    with zipfile.ZipFile(zip_path) as z:
        names = z.namelist()
        tops = set()
        for n in names:
            top = n.split("/", 1)[0]
            if top != TOP.rstrip("/"):
                tops.add(top)
        for top in sorted(tops):
            problems.append("bad top-level entry: %s" % top)

        files = {}
        for n in names:
            if n.endswith("/") or not n.startswith(TOP):
                continue
            files[n[len(TOP):]] = n

        for p in list(MUST_SHIP_FILES) + list(extra_required):
            if p not in files:
                problems.append("missing: %s" % p)
        for d in MUST_SHIP_NONEMPTY_DIRS:
            if not any(r.startswith(d) for r in files):
                problems.append("empty dir: %s" % d)
        unexpected = set()
        for rel in sorted(files):
            if _is_dev_only(rel):
                problems.append("dev-only file shipped: %s" % rel)
            else:
                top_name = rel.split("/", 1)[0]
                if top_name not in ALLOWED_TOP_LEVEL and top_name not in unexpected:
                    unexpected.add(top_name)
                    problems.append("unexpected top-level entry: %s" % top_name)
            if rel.lower().endswith(".php") and b"<?=" in z.read(files[rel]):
                problems.append("short echo tag in %s" % rel)
    return problems


def main(argv):
    extra = []
    zip_arg = None
    i = 0
    while i < len(argv):
        if argv[i] == "--require" and i + 1 < len(argv):
            extra.append(argv[i + 1])
            i += 2
        elif zip_arg is None and not argv[i].startswith("--"):
            zip_arg = argv[i]
            i += 1
        else:
            print("usage: check_package.py <zip> [--require <path>]...", file=sys.stderr)
            return 2
    if zip_arg is None:
        print("usage: check_package.py <zip> [--require <path>]...", file=sys.stderr)
        return 2
    problems = check(pathlib.Path(zip_arg), extra)
    for p in problems:
        print(p, file=sys.stderr)
    return 1 if problems else 0


if __name__ == "__main__":
    sys.exit(main(sys.argv[1:]))

import sys
import tempfile
import unittest
import zipfile
from pathlib import Path

sys.path.insert(0, str(Path(__file__).resolve().parent.parent))

import check_package  # noqa: E402

TOP = "xw_lite_slider/"


def complete_entries():
    entries = {}
    for p in check_package.MUST_SHIP_FILES:
        entries[TOP + p] = b"x"
    for d in check_package.MUST_SHIP_NONEMPTY_DIRS:
        entries[TOP + d + "file.txt"] = b"x"
    return entries


class CheckPackageTest(unittest.TestCase):
    def setUp(self):
        self._tmp = tempfile.TemporaryDirectory()
        self.addCleanup(self._tmp.cleanup)

    def make_zip(self, entries):
        path = Path(self._tmp.name) / "t.zip"
        with zipfile.ZipFile(path, "w") as z:
            for name, data in entries.items():
                z.writestr(name, data)
        return path

    def problems(self, entries, **kw):
        return check_package.check(self.make_zip(entries), **kw)

    def test_complete_package_ok(self):
        self.assertEqual(self.problems(complete_entries()), [])

    def test_missing_glide_fails(self):
        e = complete_entries()
        del e[TOP + "js/glide.js"]
        self.assertIn("missing: js/glide.js", self.problems(e))

    def test_empty_vendor_lib_fails(self):
        e = {k: v for k, v in complete_entries().items()
             if not k.startswith(TOP + "vendor/xanweb/ls-module/")}
        self.assertIn("empty dir: vendor/xanweb/ls-module/", self.problems(e))

    def test_build_shipped_fails(self):
        e = complete_entries()
        e[TOP + "build/package.json"] = b"{}"
        self.assertIn("dev-only file shipped: build/package.json", self.problems(e))

    def test_agents_md_dev_only(self):
        for name in ("AGENTS.md", "CLAUDE.md"):
            e = complete_entries()
            e[TOP + name] = b"x"
            self.assertIn("dev-only file shipped: " + name, self.problems(e))

    def test_unexpected_top_level_entry_fails(self):
        e = complete_entries()
        e[TOP + "composer.json"] = b"{}"
        e[TOP + "stray/file.txt"] = b"x"
        got = self.problems(e)
        self.assertIn("unexpected top-level entry: composer.json", got)
        self.assertIn("unexpected top-level entry: stray", got)

    def test_second_top_level_fails(self):
        e = complete_entries()
        e["other/file.txt"] = b"x"
        self.assertIn("bad top-level entry: other", self.problems(e))

    def test_short_tag_in_block_template_fails(self):
        e = complete_entries()
        p = "blocks/xw_lite_slider/templates/custom/view.php"
        e[TOP + p] = b"<div><?=h($x)?></div>"
        self.assertIn("short echo tag in " + p, self.problems(e))

    def test_short_tag_in_js_and_css_ignored(self):
        e = complete_entries()
        e[TOP + "js/a.js"] = b"var s = '<?=';"
        e[TOP + "css/a.css"] = b"/* <?= */"
        self.assertEqual(self.problems(e), [])

    def test_notice_and_changelog_required(self):
        for name in ("NOTICE", "CHANGELOG.md"):
            e = complete_entries()
            self.assertIn(TOP + name, e)
            del e[TOP + name]
            self.assertIn("missing: " + name, self.problems(e))

    def test_extra_required(self):
        self.assertIn("missing: EXTRA.txt",
                      self.problems(complete_entries(), extra_required=["EXTRA.txt"]))


if __name__ == "__main__":
    unittest.main()

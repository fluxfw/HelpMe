#!/usr/bin/env sh

# https://github.com/ILIAS-eLearning/ILIAS/pull/3512.diff
patch_file="`dirname $0`/ilias7_core_apply_ilctrl_patch_3512.diff"
echo "Patch file: $patch_file"

ilias_root_dir="$(realpath -s "$(dirname $0)/../../../../../../../../../../..")"
echo "ILIAS root directory: $ilias_root_dir"

if which git > /dev/null; then
    echo "Uses git"
    apply_patch="git -C $ilias_root_dir apply -v"
elif which patch > /dev/null; then
    echo "Uses patch"
    apply_patch="patch -p1 -d $ilias_root_dir -f -r /dev/null"
else
    echo "Neither git or patch found"
    exit 1
fi

echo "Download and apply"
set -e
cat $patch_file | $apply_patch

echo "Done"

#!/bin/sh
patch_url="https://github.com/ILIAS-eLearning/ILIAS/pull/3512.diff"
echo "Patch url: $patch_url"

ilias_root_dir="$(realpath -s "$(dirname $0)/../../../../../../../..")"
echo "ILIAS root directory: $ilias_root_dir"

if which curl > /dev/null; then
  echo "Uses curl"
  download_patch="curl -L $patch_url"
elif which wget > /dev/null; then
  echo "Uses wget"
  download_patch="wget -O - $patch_url"
else
  echo "Neither curl or wget found"
  exit 1
fi

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
$download_patch | $apply_patch

echo "Done"

#!/usr/bin/env bash
set -e
trap '>&2 echo Error: Command \`$BASH_COMMAND\` on line $LINENO failed with exit code $?' ERR
BUILD_DIR=/var/www/html

echo "==> preparing changed files list"
changed_files_ce="$BUILD_DIR/app/code/Webjump/Tests/Php/_files/changed_files_ce.txt"

# changed file path
echo $changed_files_ce

php app/code/Webjump/Tests/Php/travis/get_github_changes.php \
    --output-file="$changed_files_ce" \
    --base-path="$BUILD_DIR" \
    --repo='git@github.com:obramax/magento.git' \
    --branch="coverage/$1"

# shellcheck disable=SC2002
cat "$changed_files_ce" | sed 's/^/  + including /'

php app/code/Webjump/Tests/Php/GeneratePhpUnitFileForFilesDevToMasterAndCurrentBranch.php

#!/usr/bin/env bash
set -e
trap '>&2 echo Error: Command \`$BASH_COMMAND\` on line $LINENO failed with exit code $?' ERR

echo "Travis Build $TRAVIS_BUILD_NUMBER"
export DISPLAY=:99.0
sh -e /etc/init.d/xvfb start
wget https://repo.maven.apache.org/maven2/io/qameta/allure/allure-commandline/2.13.0/allure-commandline-2.13.0.zip
unzip allure-commandline-2.13.0.zip -d ~/allure
cp app/code/Webjump/Tests/Php/travis/config.php.travis app/etc/config.php
php bin/magento setup:di:compile

echo "==> preparing changed files list"
changed_files_ce="$TRAVIS_BUILD_DIR/app/code/Webjump/Tests/Php/_files/changed_files_ce.txt"
changed_files_dev_to_master="$TRAVIS_BUILD_DIR/app/code/Webjump/Tests/Php/_files/changed_files_dev_to_master.txt"

echo $changed_files_ce
echo $changed_files_dev_to_master
echo $TRAVIS_BRANCH

php app/code/Webjump/Tests/Php/travis/get_github_changes.php \
    --output-file="$changed_files_ce" \
    --base-path="$TRAVIS_BUILD_DIR" \
    --repo='git@github.com:obramax/magento.git' \
    --branch='develop'
cat "$changed_files_ce" | sed 's/^/  + including /'

php app/code/Webjump/Tests/Php/travis/get_github_changes.php \
    --output-file="$changed_files_dev_to_master" \
    --base-path="$TRAVIS_BUILD_DIR" \
    --repo='git@github.com:obramax/magento.git' \
    --branch='master'
cat "$changed_files_dev_to_master" | sed 's/^/  + including /'

php app/code/Webjump/Tests/Php/GeneratePhpUnitFileForFilesDevToMasterAndCurrentBranch.php

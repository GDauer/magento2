#!/usr/bin/env bash
set -e
trap '>&2 echo Error: Command \`$BASH_COMMAND\` on line $LINENO failed with exit code $?' ERR

echo "STAGE BEFORE DEPLOY"
echo "Create dir tests/static/report/$TRAVIS_BUILD_NUMBER";
mkdir tests/static/report/$TRAVIS_BUILD_NUMBER
cp -r tests/allure-report-data/* tests/static/report/$TRAVIS_BUILD_NUMBER
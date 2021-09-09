#!/bin/bash

set -e

FILES=$(git diff --diff-filter=ACMR origin/$BASE_REF...origin/$HEAD_REF --name-only);

vendor/bin/phpcs --colors --standard=./dev/tests/static/framework/Magento $FILES

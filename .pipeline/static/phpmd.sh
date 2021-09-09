#!/bin/bash

set -e

FILES=$(git diff --diff-filter=ACMR origin/$BASE_REF...origin/$HEAD_REF --name-only);

for FILE in $FILES
do
    if [ ! -f $FILE ] ; then
        continue;
    fi

    if [ "$FILES_TO_ANALYSE" != "" ] ; then
        FILES_TO_ANALYSE="$FILES_TO_ANALYSE "
    fi

    FILES_TO_ANALYSE="$FILES_TO_ANALYSE$FILE"
done

COMMA_SEPARATED_FILES=$(echo $FILES_TO_ANALYSE | sed "s/ /,/g");

./vendor/bin/phpmd "$COMMA_SEPARATED_FILES" text dev/tests/static/testsuite/Magento/Test/Php/_files/phpmd/ruleset.xml

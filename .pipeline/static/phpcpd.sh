#!/bin/bash

set -e

FILES=$(git diff --diff-filter=ACMR origin/$BASE_REF...origin/$HEAD_REF --name-only);

for FILE in $FILES
do
    if [ ! -f $FILE ] ; then
        continue;
    fi

    if [ "$FILE" != "*.php" ] ; then
        continue;
    fi

    if [ "$FILES_TO_ANALYSE" != "" ] ; then
        FILES_TO_ANALYSE="$FILES_TO_ANALYSE "
    fi

    FILES_TO_ANALYSE="$FILES_TO_ANALYSE$FILE"
done

if [ "$FILES_TO_ANALYSE" != "" ] ; then
    ./vendor/bin/phpcpd $FILES_TO_ANALYSE
fi

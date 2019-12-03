#!/bin/bash
FILE=$1     
if [ -f $FILE ];
then
    echo "Checking patch $FILE ... "
    if ! patch -p1 -R --dry-run <$FILE; 
    then
        echo "Applying patch ... "
        patch -p1 <$FILE
        echo "DONE"
    else
        echo "Patch already applied"
    fi
else
    echo "File $FILE does not exist."
fi
#!/bin/bash

set -e

if [ "$BRANCH_REF" == "" ]; then
    echo "BRANCH_REF environment variable is empty.";
    exit 1;
fi

CLOUD_BRANCH=""

if [ "$BRANCH_REF" == "refs/heads/develop" ]; then
  CLOUD_BRANCH="integration"
elif [ "$BRANCH_REF" == "refs/heads/master" ]; then
  CLOUD_BRANCH="production"
elif [ "$BRANCH_REF" == "refs/heads/release" ]; then
  CLOUD_BRANCH="staging"
  echo "There's no branch destination for the given branch."
  exit 1
fi

git remote add cloud $CLOUD_REMOTE_URL
git push cloud -f $BRANCH_REF:$CLOUD_BRANCH

if [ "$CLOUD_BRANCH" == "production" ]; then
    git push cloud -f master:master
fi

echo "Branches synchronized.";

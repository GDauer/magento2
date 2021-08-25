#!/usr/bin/env bash
set -e
trap '>&2 echo Error: Command \`$BASH_COMMAND\` on line $LINENO failed with exit code $?' ERR
touch ~/.composer/auth.json
echo '
{
  "http-basic": {
    "repo.magento.com": {
      "username": "4ffe97e4a29cfdf4efb90d6b7468a632",
      "password": "679f6511d42b67bb66992f0375d8bd74"
    },
    "composer.amasty.com": {
      "username": "70dffef13355e766f22e998d02910a51",
      "password": "33e0d7837f6c39c9c46e45535d454237"
    }
  },
  "bitbucket-oauth": {
    "bitbucket.org": {
      "consumer-key": "uMUfB9GRzBZXXVg7L9",
      "consumer-secret": "S3qEEBLRzwURQ7YkBZagMeTWQ3gF2Gfm"
    }
  }

}
' > ~/.composer/auth.json

ssh-keyscan -t rsa,dsa bitbucket.org >> ~/.ssh/known_hosts

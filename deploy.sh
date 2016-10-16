#!/bin/bash

# Set config variables for deployer
git config user.email "deployer@samarkanddesign.com"
git config user.name "Samarkand CI"

[ -z "$HEROKU_APP" ] && echo "HEROKU_APP environment variable is not set" && exit 1;

# add built files to git
git checkout -b dist
git add --force public/build
git commit -m "Add built assets"
# deploy to heroku

check_access_to_heroku_app $HEROKU_APP
git remote add $HEROKU_APP git@heroku.com:${HEROKU_APP}.git
git push $HEROKU_APP dist:refs/heads/master -f

check_url http://${HEROKU_APP}.herokuapp.com
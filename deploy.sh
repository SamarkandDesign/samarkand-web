#!/bin/bash
echo "Deploying to Heroku"

# Ensure env variable is set
[ -z "$HEROKU_APP" ] && echo "HEROKU_APP environment variable is not set" && exit 1 || echo "Using app ${HEROKU_APP}";

# Set config variables for deployer
git config user.email $CI_COMMITTER_NAME
git config user.name $CI_COMMITTER_EMAIL

# add built files to git
git checkout -b dist
git add --force public
git commit -m "Add built assets"

# deploy to heroku
check_access_to_heroku_app $HEROKU_APP
git remote add $HEROKU_APP git@heroku.com:${HEROKU_APP}.git
git push $HEROKU_APP dist:refs/heads/master -f

# remove git config (in case run locally)
git config --unset user.email
git config --unset user.name

check_url http://${HEROKU_APP}.herokuapp.com

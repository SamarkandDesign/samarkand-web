#!/bin/bash

set -e

if [ -z "${SLACK_WEBHOOK_URL}" ]; then
  echo "No SLACK_WEBHOOK_URL defined - not notifying slack"
else
  # Notify of the release in Slack


  COMMIT_MSG=$(git log -1 --pretty=%B)

  curl --request POST \
    --url "$SLACK_WEBHOOK_URL" \
    --header 'content-type: application/json' \
    --data '{
  "username": "Samarkand Deployer",
  "text": "Production deploy of samarkand-web 🚀 (<https://circleci.com/workflow-run/'"$CIRCLE_WORKFLOW_ID"'|details>)",
  "attachments": [{
    "title": "'"$COMMIT_MSG"'",
    "color": "#00AB55",
    "fields": [
      {
        "title": "Release",
        "value": "'"$CIRCLE_TAG"'",
        "short": true
      },
      {
        "title": "User",
        "value": "'"$CIRCLE_USERNAME"'",
        "short": true
      }
    ]
  }]
  }'
fi
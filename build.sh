#!/bin/bash

# Build assets for production

# install node dependencies
nvm use 7
yarn install
npm rebuild node-sass

# build assets
yarn production

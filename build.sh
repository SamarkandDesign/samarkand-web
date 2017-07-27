#!/bin/bash

# Build assets for production

# install node dependencies
yarn install
npm rebuild node-sass

# build assets
yarn production

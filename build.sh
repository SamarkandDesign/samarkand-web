#!/bin/bash

# Build assets for production

# install node dependencies
npm install
npm rebuild node-sass

# build assets
gulp --production
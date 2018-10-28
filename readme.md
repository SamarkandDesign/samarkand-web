# Samarkand Design

This is the official web application of Samarkand Design. Built with Laravel 5.4.

## Run Locally

To avoid having to install lots of dependencies on your machine there is a docker-compose configuration that provides the necessary services.

1. Clone the repo to your working directory
2. Run `yarn composer`/`npm run composer` to install dependencies (or view `package.json` to see the full command if Yarn/npm is not installed).
3. Start up the containers with `docker-compose up`.
4. Run `docker-compose run samarkand-app sh -c 'php artisan migrate'` to migrate the db. (database container must be running).

### Included containers

The docker-compose configuration includes a number of services to make local development easier. The following are exposed to the host:

- The app itself: http://localhost:8080.
- MySQL database `localhost:3306.
- Mailhog, a local mailserver that logs emails sent by the application: http://localhost:8025

### Running commands

To run a command just use the standard docker-compose syntax:

```
docker-compose run samarkand-app sh -c '<command here>'
```

### Notes on environment variables

- The cache driver should be one that supports cache tags (e.g. memcached, redis).
- Cache time specifies the default number of minutes that a cache entry will be retained in the cache. This is so values like this are not hard-coded and can be adjusted in production depending on server loads.
- Not all config entries are in `.env.example`. Check individual config files for more options.

## Contributing

Development is by Git Flow. To add a feature contribute create a branch from the dev branch called `feature/<feature-name>` and pull request back to the dev branch.

All the tests should pass before trying to merge. This is checked using Codeship.

PSR-2 code style should be used throughout. This is auto-checked with StyleCI

### Front End

Vue components are used and compiled with Laravel mix. You should install the node dependencies with `yarn install` before making changes.

Styles are compiled from SASS.

The whole lot can then be recompiled by running `yarn production`.

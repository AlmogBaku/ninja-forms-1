# Ninja Forms

Ninja Forms is the ultimate FREE form creation tool for WordPress. Build forms within minutes using a simple yet powerful drag-and-drop form creator. For beginners, quickly and easily design complex forms with absolutely no code. For developers, utilize built-in hooks, filters, and even custom field templates to do whatever you need at any step in the form building or submission using Ninja Forms as a framework.

## How To Use Ninja Forms

This README is written for developers. If you are a user of Ninja Forms, you can get help, including and video tutorials, in the documentation section of our website: [Ninja Forms Documentation](http://ninjaforms.com/documentation/)

If you have any feature requests, please feel free to visit [ninjaforms.com](http://ninjaforms.com) and let us know!

## Testing and Development

This section describes how to install a docker development environment to develop and test Ninja Forms functionality.

### Requirements:

- git
- node
- yarn
  - Please use yarn, not npm.
- Docker
- PHP
- Gulp

### Installation

- Clone the repo
  - `git clone git@git.saturdaydrive.io:_/ninja-forms/ninja-forms.git`
- Switch into the `ninja-forms` directory
  - `cd ninja-forms`
- Install PHP dependencies:
  - `composer install`
- Install JS dependencies:
  - `yarn`

### Local Development

Once you have your packages installed, install a local WordPress site for development. We use [@wordpress-scripts/scripts](https://developer.wordpress.org/block-editor/packages/packages-scripts/) for a Docker-based development environment.

You must have have [installed Docker Desktop](https://www.docker.com/products/docker-desktop) and have Docker running before installing or starting the local environment:

- Install
  - `yarn env install`
  - This step will take awhile to run.
- Start the local site
  - `yarn env start`
    -You should be able to view your development site at: `http://localhost:8889`

You can run `yarn env stop` to stop your environment

### Testing

- To test your php code with phpunit
  - `yarn env test-php`
- To test your javascript code with Jest
  - `yarn test:unit`

Some tips:

- If you find that your JavaScript unit testing are failing and the errors mention `obsolete snapshot`, try:
  - `yarn test:unit -u`
  - The `-u` flag clears out old snapshot allowing for the creation of new ones.
- Please add other common issues you find, that might be helpful to others here.
- We use [Jest](https://jestjs.io/docs/en/getting-started) and [React Testing Library](https://github.com/testing-library/react-testing-library) for JavaScript unit tests.
- We use [phpunit 7](https://phpunit.de/getting-started/phpunit-7.html) for PHP unit testing.

### Compiling JavaScript And CSS

Most JavaScript and CSS is developed in the "assets" directory or "client". To compile to those assets:

    -`yarn gulp`

The blocks are developed in the "blocks" directory. We use [@wordpress-scripts](https://www.npmjs.com/package/@wordpress/scripts) to compile the blocks JavaScript and CSS. The build tooling for blocks could be used for other parts of the plugin.

- Start watcher for blocks, including there front-end clients:
  - `yarn start:blocks` or `yarn build:blocks --watch`
- Build all blocks for "production" -- release to WordPress.org:
  - `yarn build:blocks`

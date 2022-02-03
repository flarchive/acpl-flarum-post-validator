# Flarum Post Validator

![License](https://img.shields.io/badge/license-MIT-blue.svg) [![Latest Stable Version](https://img.shields.io/packagist/v/acpl/flarum-post-validator.svg)](https://packagist.org/packages/acpl/flarum-post-validator) [![Total Downloads](https://img.shields.io/packagist/dt/acpl/flarum-post-validator.svg)](https://packagist.org/packages/acpl/flarum-post-validator)

Validates all posts in the Flarum database. Useful after migrating from other software to Flarum.

## Installation

Install with composer:

```sh
composer require acpl/flarum-post-validator
```

## Usage
```sh
php flarum validate-posts --chunk=100
```

## How it works?

This extension retrieves all posts from the database and then tries to render them. If an error occurs it saves the id and link of the post to the file.

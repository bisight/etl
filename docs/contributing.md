This is an ongoing project, and we'll be adding more E/T/L classes whenever we need them.

As our own process data comes primarily from databases and flat-files, it's unlikely that
we'll be adding Extractors for MongoDB or other sources for example. However, we're more
than happy to accept Pull Requests if you'd like to add them! It's really quite simple,
just take a look at the existing implementations as an example.

## Compile & publish phar

Follow this steps to publish new version of phar:

- Ensure you added new dependencies to `bin/compile`. For example: `$compiler->addDirectory('vendor/vendor-name/package', ['**/tests/*', '!*.php']);`. Please, don't add files/dependencies that required only in dev-enironment.
- Bump version at `src/Console/Application.php` (for example `1.5.0`)
- Run `bin/compile` and get updated `bisight-etl.phar` at root directory
- Ensure that application works correctly and commit changes
- Tag comit with new version (for example `1.5.0` as on step 2) & push changes to github
- Add new compiled `bisight-etl.phar` file to downloads at `https://github.com/bisight/etl/releases/latest`
- Get SHA1 of `bisight-etl.phar` file and update `manifest.json`

## Documentation

If you're writing docs, you can preview it in a browser.
Follow next steps:

- Install `mkdocs` as described in [official documentation](http://www.mkdocs.org/#installation)
- Run `mkdocs serve`
- Open `127.0.0.1:8000` in your browser to preview docs

## Todo

- Make `bin/publish` shell script automating this process
- Add `.editorconfig`
- Add `.php_cs` - Code Style Fixer config
- Fix code style according to `.php_cs` rules

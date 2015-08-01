## As part of another project

Check out [composer](http://www.getcomposer.org) for details about installing and running composer.

Simply clone this repository, and run `composer install` to fetch all the dependencies.

If you'd like to use this as part of another project, simply add the following to your composer.json file, and run `composer update`:

```json
{
    "require": {
        "bisight/etl": "~1.0"
    }
}
```

## As standalone cli command

First time you need to manually download phar:

```
wget `curl -s https://api.github.com/repos/bisight/etl/releases | grep browser_download_url | head -n 1 | cut -d '"' -f 4`
chmod +x bisight-etl.phar
mv bisight-etl.phar /usr/local/bin/bisight-etl
bisight-etl --version
```

### Upgrading

To upgrade your phar, just type:
```
bisight-etl self-update
```

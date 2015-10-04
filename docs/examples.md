
## Prepare environment

### Dependencies
First of all, you need install all dependencies by running `composer install` command.

### Database config
We will use `sandbox_etl` database for all our examples.

To configure credentials, you can do following:

```
sudo mkdir -p /share/config/database/
sudo cp examples/sandbox_etl.conf.dist /share/config/database/sandbox_etl.conf
sudo nano /share/config/database/sandbox_etl.conf
```

And write actual username/password to it.

### Check

Check config validity by runing next command:

```bash
./vendor/bin/database-manager connection:config sandbox_etl
```

If credentials are correct, you'll see next output:
```bash
NAME: [sandbox_etl]
    Connection [default]
        dsn:  [mysql:dbname=sandbox_etl;host=localhost;port=3306]
        username:  [root]
        password:  [YOUR_PASSWORD]
        host:  [localhost]
        port:  [3306]
```

## Something important

On all examples you can see commands like `mysql databasename < filename.sql` or `mysqladmin create sandbox_etl`.

This command will work properly if you configured `~/.my.cnf` [to avoid putting password on the command line](http://stackoverflow.com/questions/16299603/mysql-utilities-my-cnf-option-file).

If you don't make that configuration - you need to add your login and probably password to each command before run.

For example:

- `mysql -u root -pSOME_PASSWORD databasename < filename.sql` if your user have password and you want put it to terminal
- `mysql -u root -p databasename < filename.sql` if your user have password and you want type it in interactive mode
- `mysql -u USER databasename < filename.sql` if your user have empty password

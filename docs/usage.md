# Cli

Type `./bin/bisight etl:run path/to/job.xml` to rub your job.

If you want to see progress bar right into terminal,

```bash
./bin/bisight
BiSight console tools version 1.3.0

Usage:
  command [options] [arguments]

Options:
  -h, --help            Display this help message
  -q, --quiet           Do not output any message
  -V, --version         Display this application version
      --ansi            Force ANSI output
      --no-ansi         Disable ANSI output
  -n, --no-interaction  Do not ask any interactive question
  -v|vv|vvv, --verbose  Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

Available commands:
  help         Displays help for a command
  list         Lists commands
  self-update  Updates bisight-etl.phar to the latest version
 etl
  etl:run      Run an ETL job
```

## PdoExtractor

Extracts data from a [PDO](http://php.net/manual/en/pdo.drivers.php) database (MySQL, PostgreSQL, etc).

### Arguments

| Argument | Mandatory | Type | Description |
|-|-|-|-|
| dbname | Yes | string | Name of the database to extract data from, usually your operational system |
| sql | Yes | string | An sql query to fetch the rows |

## Example jobfile

```xml
<?xml version="1.0" ?>
<job name="PdoExtractorExample">
    <extractor>
        <class>BiSight\Etl\Extractor\PdoExtractor</class>
        <argument name="dbname">database_name</argument>
        <argument name="sql">
            <![CDATA[
                SELECT email, firstname, lastname
                FROM users
                WHERE is_subscribed=1
            ]]>
        </argument>
    </extractor>
    ...
</job>
```

## Try this

To see `PdoExtractor` in action - just run:

```
bin/try extractor pdo
```

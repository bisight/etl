## PdoLoader

Load data to database.

### Arguments

| Argument | Mandatory | Type | Description |
|-|-|-|-|
| dbname | Yes | string | Destination database name |
| tablename | Yes | string| Destination table name from database |
| indexes | No | see below | Table's indexes to add to |
| skipdrop | No | boolean | Don't drop table during initialization |

### Indexes notation

Common index format is: `Index Name: Column Name 1, Column Name 2, ..., Column Name N`.

- `Indexes` must be delimited by newline (`\n`) or semicolon (`;`).
- `Index` consists of `Index Name` and `Column Names List` delimited by colon (`:`).
- `Column Names List`  consists of `Column Names` delimited by comma (`,`).

_Examples_:

| Description | Example |
|-|-|
| Single index, spaces for readability | fullname: firstname, lastname |
| Single index, no spaces | dimensions:height,width,length |
| Multile indexes in one line | a:a1,a2; b:b1,b2,b3; c:c1 |
| Multiple indexes, multiline | a:a1,a2<br/>b:b1,b2,b3<br/>c:c1 |

## Usage example

Full job file might be like this:

```xml
<?xml version="1.0" ?>
<job name="Subscribers">
    <extractor>
        <class>BiSight\Etl\Extractor\PdoExtractor</class>
        <argument name="dbname">my_input_dbname</argument>
        <argument name="sql">
            <![CDATA[
                SELECT email, firstname, lastname
                FROM users
                WHERE is_subscribed=1
            ]]>
        </argument>
    </extractor>

    <transformer>
        <class>BiSight\Etl\Transformer\NullTransformer</class>
    </transformer>

    <loader>
        <class>BiSight\Etl\Loader\PdoLoader</class>
        <argument name="dbname">my_output_dbname</argument>
        <argument name="tablename">subscribers</argument>
        <argument name="indexes">
			fullname: firstname, lastname
        </argument>
    </loader>
</job>
```

## Try this

To see `PdoLoader` in action - just run:

```
bin/try loader pdo
```

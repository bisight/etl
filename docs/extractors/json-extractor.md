## JsonExtractor

Extracts data from all `json` files found at `basepath`.

### Arguments

| Argument | Mandatory | Type | Description | Example |
|-|-|-|-|-|
| basepath | Yes | string | Path name to search json files at |
| columns | Yes | special | See [Columns](/columns/) | id:integer,description,created_at:datetime |

## Example jobfile

```xml
<?xml version="1.0" ?>
<job name="JsonExtractorExample">
    <extractor>
        <class>BiSight\Etl\Extractor\JsonExtractor</class>
        <argument name="basepath">path/to/json/files/</argument>
        <argument name="columns">invoice_number:integer,created_at:datetime,description,client:string(64),total:decimal,payed_at:datetime</argument>
    </extractor>
    ...
</job>
```

Json files has following format:

```json
# path/to/json/files/1.json
{
    "invoice_number": 1,
    "created_at": "2015-01-01",
    "description": "First invoice to our first client",
    "client": "John Doe",
    "total": "1999,99",
    "payed_at": "2015-01-02",
    "some_extra_field": "We don't want to extract this field, so don't pass it name to columns"
}
```

## Try this

To see `JsonExtractor` in action - just run:

```
bin/try extractor json
```

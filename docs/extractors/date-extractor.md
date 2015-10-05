
## DateExtractor

This extractor generates a range of dates.

This can be useful to generate a [date dimension](http://en.wikipedia.org/wiki/Dimension_(data_warehouse)#Common_patterns).

### Arguments

| Argument | Type / Format | Mandatory | Default value | Description | Example |
|-|-|-|-|-|
| start | date / YYYYMMDD | Yes | | Start date | 19900101 |
| end | date / YYYYMMDD | Yes | | End date | 20201231 |
| interval | integer | No | 1 | Days interval | 7 |

## Example jobfile

```xml
<?xml version="1.0" ?>
<job name="DateExtractorExample">
    <extractor>
        <class>BiSight\Etl\Extractor\DateExtractor</class>
        <argument name="start">19900101</argument>
        <argument name="end">19900114</argument>
        <argument name="interval">7</argument>
    </extractor>
    ...
</job>
```

## Try this

To see `DateExtractor` in action - just run:

```
bin/try extractor date
```

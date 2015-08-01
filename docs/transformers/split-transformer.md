## SplitTransformer

Lets you split one value into few columns.

### Arguments

| Argument | Type / Format | Mandatory | Default value | Description |
|-|-|-|-|-|
| inputColumnName | string | Yes | | Column to read data from |
| outputColumns | special | Yes | | Columns to put data to. See [Columns](/columns) |
| delimiter | string | Yes | | Delimiter |
| limit | integer | No | null | Limit |

## Example jobfiles

```xml
<?xml version="1.0" ?>
<job name="SplitTransformerExample1">
    ...
    <transformer>
        <class>BiSight\Etl\Transformer\SplitTransformer</class>
        <argument name="inputColumnName">registered_at</argument>
        <argument name="outputColumns">registration_year:string(4),registration_month:string(2),registration_day:string(2)</argument>
        <argument name="delimiter">-</argument>
    </transformer>
    ...
</job>
```

```xml
<?xml version="1.0" ?>
<job name="SplitTransformerExample2">
    ...
    <transformer>
        <class>BiSight\Etl\Transformer\SplitTransformer</class>
        <argument name="inputColumnName">optin_at</argument>
        <argument name="outputColumnNames">optin_date:string(10),optin_time:string(8)</argument>
        <argument name="delimiter"> </argument>
        <argument name="limit">2</argument>
    </transformer>
    ...
</job>
```

## Try this

To see `SplitTransformer` in action - just run:

```
bin/try transformer split
```

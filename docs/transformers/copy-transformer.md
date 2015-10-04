## CopyTransformer

This transformer allows you to copy one column content to another.

### Arguments

| Argument | Type | Description |
|-|-|-|
| inputColumnName | string | Column name to copy value from |
| outputColumn | special | Column name to copy value to. See [Columns](/columns) |
| override | boolean (true or false) | Override value at `outputColumnName` even if this field not empty |

## Example jobfile

```xml
<?xml version="1.0" ?>
<job name="CopyTransformerExample">
    ...
    <transformer>
        <class>BiSight\Etl\Transformer\CopyTransformer</class>
        <argument name="inputColumnName">registered_at</argument>
        <argument name="outputColumnName">latest_activity</argument>
        <argument name="override">false</argument>
    </transformer>
    ...
</job>
```

## Try this

To see `CopyTransformer` in action - just run:

```
bin/try transformer copy
```

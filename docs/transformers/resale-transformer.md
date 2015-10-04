## ResaleTransformer

Lets you calculate the resale percentage.

### Arguments

| Argument | Type | Mandatory | Description |
|-|-|-|
| purchasedColumnName | string | Yes | Column containing the amount of items purchases |
| soldColumnName | string | Yes | Column containing the amount of items sold |
| outputColumn | special | Yes | Column that you want to output the calculated value in. See [Column](/column). |

### Output

This outputs a configurable columnname containing the calculated resale percentage

## Example jobfile

```xml
<?xml version="1.0" ?>
<job name="ResaleTransformerExample">
    ...
    <transformer>
        <class>BiSight\Etl\Transformer\ResaleTransformer</class>
        <argument name="purchasedColumnName">product_purchased</argument>
        <argument name="soldColumnName">product_sold</argument>
        <argument name="outputColumn">product_resale_score:integer</argument>
    </transformer>
    ...
</job>
```

## Try this

To see `ResaleTransformer` in action - just run:

```
bin/try transformer resale
```

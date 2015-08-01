## ExpressionTransformer

This transformer allows you to provide arbitrary expressions.

### Arguments

| Argument | Type | Description |
|-|-|
| expression | expression | Raw expression |
| outputColumn | special | The name fo the column that will receive the result of the expression. See [Columns](/columns) |

This transformer is powered by the [Symfony Expression Language Component](http://symfony.com/doc/current/components/expression_language/index.html).
So all of it's features are automatically supported.

For example, you can create simple math formulas:

    -1 * my_column_a + my_column_b

All columns in the row are available in as variable names in the expression.

## Example jobfile

```xml
<?xml version="1.0" ?>
<job name="ExpressionTransformerExample">
    ...
    <transformer>
        <class>BiSight\Etl\Transformer\ExpressionTransformer</class>
        <argument name="expression">is_null(subscribed_at) ? 'N' : 'Y'</argument>
        <argument name="outputColumn">is_subscribed:string(1)</argument>
    </transformer>
    ...
</job>
```

## Try this

To see `ExpressionTransformer` in action - just run:

```
bin/try transformer expression
```

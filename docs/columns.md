# Columns notation

## Possible types

| Type | Notes | Example |
|-|-|-|
| integer |  | `id:integer` |
| string | You need specify length in braces | `name:string(64)`  |
| text | This is default column type; you may not specify them | `description` is the same as `description:text` |
| datetime |  | `created_at:datetime` |
| decimal | You can specify length and precision in braces separated by `;` | `total:decimal(10;2)` |

## Where used?

* `CsvExtractor` as `columns` argument
* `JsonExtractor` as `columns` argument
* `CopyTransformer` as `outputColumn` argument
* `ExpressionTransformer` as `outputColumn` argument
* `ResaleTransformer` as `outputColumn` argument
* `SplitTransformer` as `outputColumns` argument

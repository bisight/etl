## DateTransformer

This transformer 'enriches' the date dimension with various properties of the provided date.
It can be used in combination with the DateExtractor

This can be very useful in generating date dimensions.

### Arguments

| Argument | Mandatory | Default value | Description |
|-|-|-|
| dateColumnName | No | date | The name of the column containing the date (YYYYMMDD) |

### Output

| Argument | Description |
|-|-|
| year | Year of the provided date (i.e. 2015) |
| month | Month of the provided date (i.e. 03) |
| yearmonth | Year + month of the provided date (i.e. 201503) |
| quarter | Quarter of the date (i.e. 1,2,3 or 4) |
| yearquarter | Year + quarter (i.e. 20151) |
| yearquartermonth | Year + quarter + month (i.e. 2015103) |
| weekday | Day number of the week. (i.e. 2 = tuesday) |
| weekdayname | Name of the day of the week (i.e. `tuesday`) |
| weekdayflag | Is this day a weekday? (i.e. Y or N for saturday+sunday) |

## Example jobfile

```xml
<?xml version="1.0" ?>
<job name="DateTransformerExample">
    ...
    <transformer>
        <class>BiSight\Etl\Transformer\DateTransformer</class>
        <argument name="dateColumnName">registered_at</argument>
    </transformer>
    ...
</job>
```

## Try this

To see `DateTransformer` in action - just run:

```
bin/try transformer date
```

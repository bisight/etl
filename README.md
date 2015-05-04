BiSight ETL
=============

BiSight ETL is a simple, extensible and powerful E(xtract), T(ransform), L(oad) application and library.

It provides a pluggable set of Extractors, Transformers and Loaders. New and/or custom extensions can
be easily integrated by implementing one of the 3 interfaces.

## What is ETL ?

According to [Wikipedia](http://en.wikipedia.org/wiki/Extract,_transform,_load)

> In computing, Extract, Transform and Load (ETL) refers to a process in database usage and especially in data warehousing that:
>
> * Extracts data from homogeneous or heterogeneous data sources
> * Transforms the data for storing it in proper format or structure for querying and analysis purpose
> * Loads it into the final target (database, more specifically, operational data store, data mart, or data warehouse)


# Installing

Check out [composer](http://www.getcomposer.org) for details about installing and running composer.

Simply clone this repository, and run `composer install` to fetch all the dependencies.

If you'd like to use this as part of another project, simply add the following to your composer.json file, and run `composer update`:

```json
{
    "require": {
        "bisight/etl": "~1.0"
    }
}
```

## Extractors

### PdoExtractor

Extracts data from a [PDO](http://php.net/manual/en/pdo.drivers.php) database (MySQL, PostgreSQL, etc).

Arguments:

* dbname: Name of the database to extract data from, usually your operational system.
* sql: an sql query to fetch the rows

### DateExtractor

This extractor generates a range of dates. This can be useful to generate a [date dimension](http://en.wikipedia.org/wiki/Dimension_(data_warehouse)#Common_patterns)

Arguments:

* start: start date, for example: 19900101 (YYYYMMDD)
* end: end date, for example: 20201231 (YYYYMMDD)
* interval: days interval, defaults to 1

## Transformers

### NullTransformer

This transformer doesn't do anything, and simply forwards the row as-is.

### ResaleTransformer

Let's you calculate the resale percentage

Arguments:

* outputColumnName: Column that you want to output the calculated value in
* purchasedColumnName: Column containing the amount of items purchases
* soldColumnName: Column containing the amount of items sold

Output:

This outputs a configurable columnname containing the calculated resale percentage

### DateTransformer

This transformer 'enriches' the date dimension with various properties of the provided date.
It can be used in combination with the DateExtractor

This can be very useful in generating date dimensions.

Arguments:

* dateColumnName: The name of the column containing the date (YYYYMMDD), usually 'date'.

Output:

* year: Year of the provided date (i.e. 2015)
* month: Month of the provided date (i.e. 03)
* yearmonth: Year + month of the provided date (i.e. 201503)
* quarter: Quarter of the date (i.e. 1,2,3 or 4)
* yearquarter: Year + quarter (i.e. 20151)
* yearquartermonth: Year + quarter + month (i.e. 2015103)
* weekday: Day number of the week. (i.e. 2 = tuesday)
* weekdayname: Name of the day of the week (i.e. `tuesday`)
* weekdayflag: Is this day a weekday? (i.e. Y or N for saturday+sunday)

## Jobs and Jobfiles

You can load and run job configurations (combinations of an Extractor, multiple transformers, and multiple loaders) from a simple XML file. In this file you express which classes to use, and 
what the arguments are. 

Here's a simple example:

```xml
<?xml version="1.0" ?>
<job name="Invoice information">
    <extractor>
        <class>BiSight\Etl\Extractor\PdoExtractor</class>
        <argument name="dbname">my_input_dbname</argument>
        <argument name="sql">
            <![CDATA[
                SELECT c.firstname, c.lastname, c.company, i.ref, i.totalprice
                FROM customer AS c
                INNER JOIN invoice AS i ON c.id = i.customer_id
                WHERE c. IS NULL AND ie.r_d_s IS NULL
            ]]>
        </argument>
    </extractor>
    
    <transformer>
        <class>BiSight\Etl\Transformer\NullTransformer</class>
    </transformer>
    
    <loader>
        <class>BiSight\Etl\Loader\PdoLoader</class>
        <argument name="dbname">my_output_dbname</argument>
        <argument name="tablename">flat_invoices</argument>
    </loader>
</job>
```

This job will simply merge the customer and invoice information into a flat invoice table.

Flat tables can be used more easily in Business Intelligence tools, such as BiSight Portal, Pentaho, BIRT, Tableau, etc.

## Running job files:

There's a command-line utility to execute job files:

    ./bin/bisight etl:run my/jobfile.xml
    
## Executing multiple job files

You can wrap 1 or more `job` elements into a `jobs` element, to run multiple jobs
in one command, in sequence.

You can also use XInclude to include multiple `job` files into a single `jobs` file.
Here's a simple example:

```xml
<?xml version="1.0" ?>
<jobs xmlns:xi="http://www.w3.org/2001/XInclude">
    <xi:include href="first_job.xml" />
    <xi:include href="second_job.xml" />
    <job>
        <!-- extractor, transformer, loader configs here -->
    </job>
</jobs>
```

These can then all be executed in sequence using the `etl:run` command described earlier.

## Further development

This is an ongoing project, and we'll be adding more E/T/L classes whenever we need them.

As our own process data comes primarily from databases and flat-files, it's unlikely that
we'll be adding Extractors for MongoDB or other sources for example. However, we're more
than happy to accept Pull Requests if you'd like to add them! It's really quite simple,
just take a look at the existing implementations as an example.

## Brought to you by the LinkORB Engineering team

Check out our other projects at [linkorb.com/engineering](http://www.linkorb.com/engineering).

Btw, we're hiring!

## License

MIT. Please check LICENSE.md for full license information

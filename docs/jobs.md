## Jobs and Jobfiles

You can load and run job configurations (combinations of an Extractor, multiple transformers, and multiple loaders) from a simple XML file. In this file you express which classes to use, and what the arguments are.

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

```bash
bisight-etl etl:run my/jobfile.xml
```

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

## Variables in jobfiles

It can be practical to use variables in your jobfiles. For example for basepaths, or dbnames.

You can do that like this:

```xml
<?xml version="1.0" ?>
<job name="Invoice information">
    <extractor>
        <class>BiSight\Etl\Extractor\PdoExtractor</class>
        <argument name="dbname">{{dbname}}</argument>
        <!-- etc... -->
```

Then you can provide the variable on the cli to the job runner:

```bash
bisight-etl etl:run my/jobfile.xml dbname=exampledb
```

You can use and pass as many variables as you want.

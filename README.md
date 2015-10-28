# BiSight ETL

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

## Docs

Read the docs at [etl.readthedocs.org](http://etl.readthedocs.org/).

## How to try?

Just run:

```
bin/try
```

and follow instructions.

## TODO

- [x] Standardize columns
- [ ] Ability to provide just names of extractors/loaders/transformers, not classes
- [ ] Make `bin/publish` shell script automating phar building and publishing process


## Brought to you by the LinkORB Engineering team

<img src="http://www.linkorb.com/d/meta/tier1/images/linkorbengineering-logo.png" width="200px" /><br />
Check out our other projects at [engineering.linkorb.com](http://engineering.linkorb.com).

Btw, we're hiring!

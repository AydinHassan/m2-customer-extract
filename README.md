<h1 align="center">Magento 2 Customer Extract</h1>

<p align="center">Export a single customer as SQL inserts from Magento 2</p>

## Installation

```sh
$ git clone git@github.com:AydinHassan/m2-customer-extract.git
$ cd m2-customer-extract
$ composer install
```

Then edit your DB details in `export.php`. 

## Usage
```sh
$ php export.php mycustomer@gmail.com
```

## TODO

Currently we only export customer EAV and customer address EAV.

- [ ] Support more tables, quotes, orders
- [ ] Option for keeping increment ID's or inserting without
- [ ] Allow DB details to passed as args
- [ ] Support multiple customers
- [ ] Pass destination file as arg

# VCT Products Upload

- [Overview](#overview)
- [Tasks performed](#tasks-performed)
- [Installation](#installation)
- [Configuration](#configuration)
- [TODO](#todo)

## Overview

> **The module is made for test purposes only!**
>
> Many values for the operation of the module were written directly in the code. See [TODO](#todo).

A Microsoft Excel product file can be uploaded using Admin. On the page at the address `/products/file/request`, you can make a request to download the file. When downloading, seller data will be added to the previously uploaded Admin file. A new name for the downloaded file will be generated based on seller data.

To work with Microsoft Excel files, the [PhpSpreadsheet](https://phpspreadsheet.readthedocs.io/en/latest) library is used.

## Tasks performed

- [x] Uploading a Microsoft Excel file using Admin
- [x] Generating the file download name based on seller data
- [x] Creating page for Microsoft Excel file request `/products/file/request`
- [x] Adding seller data to the downloaded file
- [x] To work with Microsoft Excel files, the [PhpSpreadsheet](https://phpspreadsheet.readthedocs.io/en/latest) library is used

## Installation

Read more about installing the module in the [Magento 2 documentation](https://experienceleague.adobe.com/docs/commerce-operations/installation-guide/tutorials/extensions.html).

## Configuration

Downloading a file with products is carried out using the Admin: `STORES` `Settings` `Configuration` `VCT` `Product Upload` `General` `Microsoft Excel File`.

Only [Microsoft Excel formats](https://support.microsoft.com/en-us/office/file-formats-that-are-supported-in-excel-0943ff2c-6014-4e8d-aaea-b83d51d46247#bmexcelformats) are allowed: `xla`, `xlam`, `xlr`, `xls`, `xlsb`, `xlsm`, `xlsx`, `xlt`, `xltm`, `xltx`, `xlw`, `xml`

## TODO

- [ ] Move the values specified in the code to the module settings

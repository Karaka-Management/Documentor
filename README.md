# What is the documentor

This documentation generator creates a html documentation for php classes based on the comments provided for classes, interfaces, traits, methods, variables etc. The generated output is html, css and js and can be styled with custom themes as desired.

## Requirements

* PHP Version >= 7.0

## Demo

http://docs.orange-management.de (Guide not implemented)

## Usage

A list of arguments can be found with:

```
php Documentor/src/index.php -h
```

The default usage would be:

```
php Documentor/src/index.php -s <SOURCE_PATH> -d <DESTINATION_PATH> -c <COVERAGE_PATH> -b <BASE_URL>
```

The documentation starts to shine when you use a unit test log as well as the coverage report and a html guide. The default template does not hide these page if they are missing and the overall experience will be not as good.

### Arguments

* `-h` Show help
* `-s` Source directory
* `-d` Destination directory
* `-c` Code coverage source (`coverage-clover`)
* `-u` Unit test log (`junit` style)
* `-g` Html guide source
* `-b` Base uri for web access (e.g. http://www.yoururl.com/docs)

## Supported Key Words

The following key words hold special meaning in the code documentation.

@var @param @version @since @latex @example @output @annotation @license @link @package @return @throws @todo @uses @see @deprecated

### Preview

Coverage

![alt tag](https://raw.githubusercontent.com/Orange-Management/Documentor/master/img/coverage.png)

Tests

![alt tag](https://raw.githubusercontent.com/Orange-Management/Documentor/master/img/test.png)

Class documentation

![alt tag](https://raw.githubusercontent.com/Orange-Management/Documentor/master/img/class.png)

Method documentation

![alt tag](https://raw.githubusercontent.com/Orange-Management/Documentor/master/img/method.png)

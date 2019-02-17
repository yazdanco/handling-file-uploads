# Save Uploaded Files

[![Build Status](https://travis-ci.org/aminyazdanpanah/save-uploaded-files.svg?branch=master)](https://travis-ci.org/aminyazdanpanah/save-uploaded-files)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/aminyazdanpanah/save-uploaded-files.svg?style=flat-square)](https://packagist.org/packages/aminyazdanpanah/save-uploaded-files)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/aminyazdanpanah/save-uploaded-files/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/aminyazdanpanah/save-uploaded-files/?branch=master)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](https://github.com/aminyazdanpanah/PHP-FFmpeg-video-streaming/blob/master/LICENSE)
[![Code Intelligence Status](https://scrutinizer-ci.com/g/aminyazdanpanah/save-uploaded-files/badges/code-intelligence.svg?b=master)](https://scrutinizer-ci.com/code-intelligence)
[![Total Downloads](https://img.shields.io/packagist/dt/aminyazdanpanah/save-uploaded-files.svg?style=flat-square)](https://packagist.org/packages/aminyazdanpanah/save-uploaded-files)


The powerful upload manger for PHP that allows you to save all your uploaded files with a validator and extract details of files into an `array`. 

## Features
* Easily manage your all uploaded files by using config_file variable.
* Validate your uploaded files by adding `validator` key in your config_file array.
* Extract the details of uploaded files into an array.
* PHP > 7.1.0.

## Installation

This version of the package is only compatible with PHP 7.1.0 or newer.

Install the package via composer:

``` bash
composer require aminyazdanpanah/save-uploaded-files
```

## Basic Usage

``` php
require_once 'vendor/autoload.php';

$config_files = [
    [
        'name' => 'upload_image',
        'save_to' => __DIR__ . "/images/user/" . time(),
        'save_as' => generateRandomString(),
        'validator' => [
            'min_size' => 100,
            'max_size' => 2048,
            'types' => ['jpg', 'jpeg', 'png']
        ]
    ]
];

$uploads = save_uploads($config_files)->all();
```
**Please see [example.php](https://github.com/aminyazdanpanah/save-uploaded-files/blob/master/examples/example.php) for more information. In that page you can see complete examples of usage of this package.**

## Documentation
First create your own html or ajax or other service that can pass files to server
##### example(html):
```html
<!DOCTYPE html>
<html>
<body>

<form action="" method="post" enctype="multipart/form-data">
    Select files to upload: <br><br><br>
    Select an image: <input type="file" name="upload_image" id="upload_image"><br><br>
    Select a video: <input type="file" name="upload_video" id="upload_video"><br><br>
    Select a document(word): <input type="file" name="upload_doc" id="upload_doc"><br><br>
    Select a raw file: <input type="file" name="upload_raw" id="upload_raw"><br><br>
    <input type="submit" value="Upload" name="submit"><br><br>
</form>

</body>
</html>
``` 

In the sever side you should create a config file and pass it to `save_uploads()` method:
```php
$config_files = [
    [
        'name' => 'upload_image',
        'save_to' => __DIR__ . "/images/user/" . time(),
        'validator' => [
            'min_size' => 100,
            'max_size' => 2048,
            'types' => ['jpg', 'jpeg', 'png']
        ]
    ],
    [
        'name' => 'upload_doc',
        'save_to' => __DIR__ . "/docs",
        'save_as' => 'my_doc_name',
        'override' => true,
        'validator' => [
            'min_size' => 10,
            'max_size' => 1024,
            'types' => ['doc', 'docx']
        ]
    ],
    [
        'name' => 'upload_video',
        'save_to' => __DIR__ . "/videos",
        'save_as' => generateRandomString(),
        'validator' => [
            'min_size' => 512, //512KB
            'max_size' => 1024 * 70, //70MB
            'types' => video_types()
        ]
    ],
    [
        'name' => 'upload_raw',
        'save_to' => __DIR__ . "/raw"
    ]
];

$uploads = save_uploads($config_files);
```

You can extract details of your upload:
```php
print_r($uploads->all());// Returns all uploads and their details.
print_r($uploads->get(['upload_video', 'upload_raw']));// Returns specified uploads and their details.
print_r($uploads->except(['upload_raw', 'upload_image']));// Returns all uploads and their details except those ones specified.
print_r($uploads->first());// Returns first upload and it's detail.
print_r($uploads->succeeded());// Returns all succeeded uploads and their details.
print_r($uploads->failed());// Returns all failed uploads and their details.
print_r($uploads->names());// Returns all upload names.
print_r($uploads->count());// Returns the number of uploads
```

An example of "details of the upload":

```text
[upload_doc] => Array
    (
        [status] => 1
        [message] => The file "CV -  Work  - Amin Yazdanpanah.docx" has been uploaded.
        [file_details] => Array
            (
                [name] => CV -  Work
                [type] => application/vnd.openxmlformats-officedocument.wordprocessingml.document
                [tmp_name] => C:\xampp\tmp\phpE8E.tmp
                [size] => 27450
                [extension] => docx
                [basename] => CV -  Work.docx
                [save_as] => my_doc_name.docx
                [dir_path] => C:\xampp\htdocs\save-upload-files\examples/docs
                [file_path] => C:\xampp\htdocs\save-upload-files\examples/docs/my_doc_name.docx
                [upload_datetime] => 2019-02-16 23:25:43
                [stat] => Array
                    (
                        [0] => 2
                        [1] => 0
                        [2] => 33206
                        [3] => 1
                        [4] => 0
                        [5] => 0
                        [6] => 2
                        [7] => 27450
                        [8] => 1550355943
                        [9] => 1550355943
                        [10] => 1550355943
                        [11] => -1
                        [12] => -1
                        [dev] => 2
                        [ino] => 0
                        [mode] => 33206
                        [nlink] => 1
                        [uid] => 0
                        [gid] => 0
                        [rdev] => 2
                        [size] => 27450
                        [atime] => 1550355943
                        [mtime] => 1550355943
                        [ctime] => 1550355943
                        [blksize] => -1
                        [blocks] => -1
                    )

            )

    )
```

##### Attributes of config_files

|     attr    	|  default  	|                         mean                         	|
|:-----------:	|:---------:	|:----------------------------------------------------:	|
|   name   	|     **mandatory:** must be specified      	|         The name you pass to the PHP($_Files).        	|
| save_to 	| **mandatory:** must be specified 	|          The path you want to save on your server or computer.          	|
| save_as      | Base file name 	|          The name you want to change it.                     	|
|   override 	|     false     	|         Override original file with the same filename .         	|
|   validator  	|     no rules     	|         Validate your file before save(see validator table).         	|

##### Attributes of validator

|     attr    	|  default  	|                         mean                         	|
|:-----------:	|:---------:	|:----------------------------------------------------:	|
|   min_size   	|     1      	|         The minimum size of files that are allowed (KiloByte).        	|
| max_size 	| 999999 	|          The maximum size of files that are allowed (KiloByte).          	|
| types      | '*' (everything) 	|          The types that are allowed (must be an array)                     	|



## Example
For see complete example, please go to [example.php](https://github.com/aminyazdanpanah/save-uploaded-files/blob/master/examples/example.php)

## Contributing

I'd love your help in improving, correcting, adding to the specification.
Please [file an issue](https://github.com/aminyazdanpanah/save-uploaded-files/issues)
or [submit a pull request](https://github.com/aminyazdanpanah/save-uploaded-files/pulls).

Please see [Contributing File](https://github.com/aminyazdanpanah/save-uploaded-files/blob/master/CONTRIBUTING.md) for more information.

## Security

If you discover a security vulnerability within this package, please send an e-mail to Amin Yazdanpanah via:
contact [AT] aminyazdanpanah • com.
## Credits

- [Amin Yazdanpanah](http://www.aminyazdanpanah.com/?u=github.com/aminyazdanpanah/save-uploaded-files)

## License

The MIT License (MIT). Please see [License File](https://github.com/aminyazdanpanah/save-uploaded-files/blob/master/LICENSE) for more information.

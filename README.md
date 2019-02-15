# Save Uploaded Files
This package helps you to save all of your uploaded files easily

## Installation

This version of the package is only compatible with PHP 7.1.0 or newer.

Install the package via composer:

``` bash
composer install aminyazdanpanah/save-uploaded-files
```

## Basic Usage

``` php
require_once 'vendor/autoload.php';

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
            'min_size' => 512,
            'max_size' => 1024 * 70,
            'types' => video_types()
        ]
    ],
    [
        'name' => 'upload_raw',
        'save_to' => __DIR__ . "/raw"
    ]
];

print_r(save_as($config_files));

```

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

In the sever side you should create a config file and pass it to `save_as()` method:
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

$extractions = save_as($config_files);
```

You can extract details of your upload:
```php
print_r($extractions);
/**
Array
(
    [0] => Array
        (
            [status] => 1
            [message] => The file "profile.jpg" has been uploaded.
            [file_details] => Array
                (
                    [name] => profile
                    [type] => image/jpeg
                    [tmp_name] => C:\xampp\tmp\phpE816.tmp
                    [size] => 169010
                    [extension] => jpg
                    [basename] => profile.jpg
                    [save_as] => profile.jpg
                )

        )

    [1] => Array
        (
            [status] => 1
            [message] => The file "CV -  Work.docx" has been uploaded.
            [file_details] => Array
                (
                    [name] => CV -  Work
                    [type] => application/vnd.openxmlformats-officedocument.wordprocessingml.document
                    [tmp_name] => C:\xampp\tmp\phpEC3F.tmp
                    [size] => 27450
                    [extension] => docx
                    [basename] => CV -  Work.docx
                    [save_as] => my_doc_name
                )

        )

    [2] => Array
        (
            [status] => 1
            [message] => The file "Friends.S01.E01.mkv" has been uploaded.
            [file_details] => Array
                (
                    [name] => Friends
                    [type] => application/octet-stream
                    [tmp_name] => C:\xampp\tmp\phpE827.tmp
                    [size] => 62623618
                    [extension] => mkv
                    [basename] => Friends.S01.E01.mkv
                    [save_as] => E6lwTc57iv
                )

        )

    [3] => Array
        (
            [status] => 1
            [message] => The file "Friends S01.zip" has been uploaded.
            [file_details] => Array
                (
                    [name] => Friends S01
                    [type] => application/x-zip-compressed
                    [tmp_name] => C:\xampp\tmp\phpEC4F.tmp
                    [size] => 1447047
                    [extension] => zip
                    [basename] => Friends S01.zip
                    [save_as] => Friends S01.zip
                )

        )

)

*/
``` 
##### Attributes of config_files

|     attr    	|  default  	|                         mean                         	|
|:-----------:	|:---------:	|:----------------------------------------------------:	|
|   name   	|     **mandatory:** must be specified      	|         The name you pass to PHP($_Files).        	|
| save_to 	| **mandatory:** must be specified 	|          The path you want to save on your server or computer.          	|
| save_as      | Base file name 	|          The name you want to change it.                     	|
|   override 	|     false     	|         Override original file with the same filename .         	|
|   validator  	|     no rules     	|         Validate your file before save(see validator table).         	|

##### Attributes of validator

|     attr    	|  default  	|                         mean                         	|
|:-----------:	|:---------:	|:----------------------------------------------------:	|
|   min_size   	|     1      	|         The minimum size of files that are allowed (KiloByte).        	|
| max_size 	| 999999 	|          The maximum size of files that are allowed (KiloByte).          	|
| types      | * (everything) 	|          The types that are allowed (must be an array)                     	|

## Example
For see complete example, please see [example.php](https://github.com/aminyazdanpanah/save-uploaded-files/examples/example.php)

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

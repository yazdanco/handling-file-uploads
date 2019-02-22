<?php

/**
 * Copyright 2019 Amin Yazdanpanah<http://www.aminyazdanpanah.com>.
 *
 * Licensed under the MIT License;
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      https://opensource.org/licenses/MIT
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

use AYazdanpanah\SaveUploadedFiles\Helper;
use AYazdanpanah\SaveUploadedFiles\Upload;
use AYazdanpanah\SaveUploadedFiles\Filter;

if (! function_exists('uploads')) {
    /**
     * @param $config_files
     * @return mixed
     * @throws \AYazdanpanah\SaveUploadedFiles\Exception\SaveExceptionInterface
     */
    function uploads($config_files): Filter
    {
        return Upload::files($config_files);
    }
}

if (! function_exists('str_random')) {
    /**
     * @param int $length
     * @return string
     */
    function str_random($length = 10): string
    {
        return Helper::str_random($length);
    }
}

if (! function_exists('is_type')) {
    /**
     * @param $type
     * @param $filename
     * @return bool
     */

    function is_type($type, $filename): bool
    {
        return Helper::isType($type,$filename);
    }
}

if (! function_exists('video_types')) {
    /**
     * @return mixed
     */
    function video_types(): array
    {
        return Helper::videoTypes();
    }
}
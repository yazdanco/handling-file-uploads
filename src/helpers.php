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

use AYazdanpanah\SaveUploadedFiles\Files;
use AYazdanpanah\SaveUploadedFiles\Filter;

if (! function_exists('save_uploads')) {
    /**
     * @param $config_files
     * @return mixed
     * @throws \AYazdanpanah\SaveUploadedFiles\Exception\SaveExceptionInterface
     */
    function save_uploads($config_files): Filter
    {
        return Files::files($config_files);
    }
}

if (! function_exists('generateRandomString')) {
    /**
     * @param int $length
     * @return string
     */
    function generateRandomString($length = 10)
    {
        return substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / strlen($x)))), 1, $length);
    }
}

if (! function_exists('video_types')) {
    /**
     * @return mixed
     */
    function video_types(): array
    {
        return [
            'webm',
            'mkv',
            'flv',
            'vob',
            'ogv',
            'ogg',
            'drc',
            'gif',
            'gifv',
            'avi',
            'MTS',
            'M2TS',
            'mov',
            'qt',
            'yuv',
            'rm',
            'rmvb',
            'asf',
            'amv',
            'mp4',
            'm4p ',
            'm4v',
            'mpg',
            'mp2',
            'mpeg',
            'mpe',
            'mpg',
            'mpeg',
            'm2v',
            '3gp',
            '3g2',
            'mxf',
            'roq',
            'nsv',
            'flv',
            'f4v',
            'f4p',
            'f4a',
            'f4b',
        ];
    }
}
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

namespace AYazdanpanah\SaveUploadedFiles;


class Helper
{
    /**
     * @param $type
     * @param $filename
     * @return bool
     */
    public static function isType($type, $filename): bool
    {
        return boolval(strstr(mime_content_type($filename), $type));
    }

    /**
     * @param int $length
     * @return bool|string
     */
    public static function str_random($length = 10)
    {
        return substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / strlen($x)))), 1, $length);
    }

    /**
     * @return array
     */
    public static function videoTypes(): array
    {
        return [
            'webm', 'mkv', 'flv', 'vob', 'ogv', 'ogg', 'drc', 'gif', 'gifv', 'avi',
            'MTS', 'M2TS', 'mov', 'qt', 'yuv', 'rm', 'rmvb', 'asf', 'amv', 'mp4',
            'm4p ', 'm4v', 'mpg', 'mp2', 'mpeg', 'mpe', 'mpg', 'mpeg', 'm2v', '3gp',
            '3g2', 'mxf', 'roq', 'nsv', 'flv', 'f4v', 'f4p', 'f4a', 'f4b',
        ];
    }
}
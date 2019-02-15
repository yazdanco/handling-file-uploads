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


use AYazdanpanah\SaveUploadedFiles\Exception\Exception;

class Files
{
    /**
     * @param $files
     * @return array
     * @throws Exception
     */
    public static function files($files)
    {
        $validator = static::validator(static::mockValidator());

        if (!is_array($files) && count($files) > 0) {
            throw new Exception("File must be an array", 500);
        }

        foreach ($files as $file) {
            if (!isset($file['name'], $file['save_to'])) {
                throw new Exception("Filename or path is not specified", 500);
            }

            if (isset($files['validator'])) {
                $validator = static::validator($file['validator']);
            }

            $extractions[] = (new File($validator))
                ->file($file['name'])
                ->save($file['save_to']);
        }

        return $extractions;
    }

    /**
     * @param $validator
     * @return Validator
     * @throws Exception
     */
    private static function validator($validator): Validator
    {
        if (!isset($validator['min_size'], $validator['max_size'], $validator['type']) || !is_array($validator['type'])) {
            throw new Exception("invalid validator inputs", 500);
        }

        return (new Validator())
            ->setMinSize($validator['min_size'])
            ->setMaxSize($validator['max_size'])
            ->setType($validator['type']);
    }

    private static function mockValidator()
    {
        return [
            'min_size' => 1,
            'max_size' => 999999,
            'type' => ['*']

        ];
    }
}
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

class Upload
{
    /**
     * @param $files
     * @return Filter
     * @throws Exception
     */
    public static function files($files)
    {
        if (!is_array($files) && count($files) > 0) {
            throw new Exception("File must be an array");
        }

        $extractions = [];

        foreach ($files as $file) {
            if (!isset($file['name'], $file['save_to'])) {
                throw new Exception("Filename or path is not specified");
            }

            $validator = static::validator(static::mockValidator());
            $override = false;
            $save_as = null;
            $export = static::mockCallback();

            if(isset($file['export'])){
                $export = $file['export'];
            }

            if (isset($file['validator'])) {
                $validator = static::validator($file['validator']);
            }

            if (isset($file['override'])) {
                $override = $file['override'];
            }

            if (isset($file['save_as'])) {
                $save_as = $file['save_as'];
            }

            $extractions[$file['name']] = (new File($validator))
                ->file($file['name'])
                ->setOverride($override)
                ->setSaveAs($save_as)
                ->save($file['save_to'], $export);
        }

        return new Filter($extractions);
    }

    /**
     * @param $validator
     * @return Validator
     * @throws Exception
     */
    private static function validator($validator): Validator
    {
        if (!isset($validator['min_size'], $validator['max_size'], $validator['allowed_extensions']) || !is_array($validator['allowed_extensions'])) {
            throw new Exception("Invalid validator inputs: check min_size, max_size, and types again");
        }

        return (new Validator())
            ->setMinSize($validator['min_size'])
            ->setMaxSize($validator['max_size'])
            ->setType($validator['allowed_extensions']);
    }

    /**
     * @return array
     */
    private static function mockValidator():array
    {
        return [
            'min_size' => 1,
            'max_size' => 999999,
            'allowed_extensions' => ['*']

        ];
    }

    private static function mockCallback(): callable
    {
        return function ($filename){
            return $filename;
        };
    }
}
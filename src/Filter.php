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


class Filter
{
    private $extractions;

    /**
     * Filter constructor.
     * @param $extractions
     */
    public function __construct(array $extractions)
    {
        $this->extractions = $extractions;
    }

    /**
     * Returns all uploads and their details.
     *
     * @return mixed
     */
    public function all(): array
    {
        return $this->extractions;
    }

    /**
     * Returns specified uploads and their details.
     *
     * @param array | string $name
     * @return array
     */
    public function get($name = ['*']): array
    {
        if (!is_array($name)) {
            $name = array($name);
        }

        return array_filter($this->extractions, function ($key) use ($name) {
            return in_array($key, $name) || current($name) === "*";
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * Returns all uploads and their details except those ones specified.
     *
     * @param array | string $name
     * @return array
     */
    public function except($name): array
    {
        if (!is_array($name)) {
            $name = array($name);
        }

        return array_filter($this->extractions, function ($key) use ($name) {
            return !in_array($key, $name);
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * Returns first upload and it's detail.
     *
     * @return array
     */
    public function first(): array
    {
        return current($this->extractions);
    }

    /**
     * Returns all succeeded uploads and their details.
     *
     * @return array
     */
    public function succeeded(): array
    {
        return array_filter($this->extractions, function ($detail) {
            return $detail['status'];
        });
    }

    /**
     * Returns all failed uploads and their details.
     *
     * @return array
     */
    public function failed(): array
    {
        return array_filter($this->extractions, function ($detail) {
            return !$detail['status'];
        });
    }

    /**
     * Returns all upload names.
     *
     * @return array
     */
    public function names()
    {
        return array_keys($this->extractions);
    }

    /**
     * Returns the number of uploads
     *
     */
    public function count()
    {
        return count($this->extractions);
    }
}
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


class Validator extends Validate implements ValidatorInterface
{
    private $min_size;
    private $max_size;
    private $type = [];

    /**
     * @return mixed
     */
    public function getMinSize()
    {
        return $this->min_size;
    }

    /**
     * @return mixed
     */
    public function getMaxSize()
    {
        return $this->max_size;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $min_size
     * @return Validator
     */
    public function setMinSize(int $min_size)
    {
        $this->min_size = $min_size;
        return $this;
    }

    /**
     * @param mixed $max_size
     * @return Validator
     */
    public function setMaxSize(int $max_size)
    {
        $this->max_size = $max_size;
        return $this;
    }

    /**
     * @param array $type
     * @return Validator
     */
    public function setType(array $type): Validator
    {
        $this->type = $type;
        return $this;
    }


}
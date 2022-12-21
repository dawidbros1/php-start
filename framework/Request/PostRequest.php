<?php

declare (strict_types = 1);

namespace Phantom\Request;

abstract class PostRequest extends GetRequest
{
    protected $post;
    protected $server;

    # Method checks if request method is post
    # If $names != [], method require to exists all post parameters in $names and next returns values of them
    public function isPost(array $names = [])
    {
        if ($status = $this->server['REQUEST_METHOD'] === 'POST') {
            if (!empty($names) && ($status = $this->hasPostNames($names, false))) {
                if (count($data = $this->postParams($names)) == 1) {
                    return $data[$names[0]];
                } else {
                    return $data;
                }
            }
        }
        return $status;
    }

    # Method returns array of values from post request
    public function postParams(array $names)
    {
        foreach ($names as $name) {
            $output[$name] = $this->postParam($name);
        }

        return $output ?? [];
    }

    # Method returns value from post request
    public function postParam(string $name, $default = null)
    {
        return $this->post[$name] ?? $default;
    }

    # Method checks if exists all parameters from post request contained in the variable "names"
    # if (returnData == true) method returns values of parameters instead of status
    public function hasPostNames(array $names, bool $returnData = true)
    {
        foreach ($names as $name) {
            if ($this->hasPostName($name, false) === false) {
                return false;
            }
        }

        return $returnData ? $this->postParams($names) : true;
    }

    # Method checks if exists input parameter in post request
    # if (returnData == true) method returns value of parameter instead of status
    public function hasPostName(string $name, bool $returnData = true)
    {
        if (!isset($this->post[$name])) {return false;}
        return $returnData ? $this->postParam($name) : true;
    }
}

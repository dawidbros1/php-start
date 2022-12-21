<?php

declare (strict_types = 1);

namespace Phantom\Request;

abstract class GetRequest
{
    protected $get;
    protected $server;

    # This same description to get methods
    public function isGet(array $names = [])
    {
        if ($status = $this->server['REQUEST_METHOD'] === 'GET') {
            if (!empty($names) && ($status = $this->hasGetNames($names, false))) {
                if (count($data = $this->getParams($names)) == 1) {
                    return $data[$names[0]];
                } else {
                    return $data;
                }
            }
        }
        return $status;
    }

    public function getParams(array $names)
    {
        foreach ($names as $name) {
            $output[$name] = $this->getParam($name);
        }

        return $output ?? [];
    }

    public function getParam(string $name, $default = null)
    {
        return $this->get[$name] ?? $default;
    }

    public function hasGetNames(array $names, bool $returnData = true)
    {
        foreach ($names as $name) {
            if ($this->hasGetName($name, false) === false) {
                return false;
            }
        }

        return $returnData ? $this->getParams($names) : true;
    }

    public function hasGetName(string $name, bool $returnData = true)
    {
        if (!isset($this->get[$name])) {return false;}
        return $returnData ? $this->getParam($name) : true;
    }
}

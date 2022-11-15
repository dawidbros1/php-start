<?php

declare (strict_types = 1);

namespace Phantom\Model;

use Phantom\Repository\StdRepository;

class StdModel extends AbstractModel
{
    public function __construct(?string $tableName = null, ?string $rulesNamaspace = null)
    {
        parent::__construct([], false);

        if ($tableName != null) {
            $this->repository = new StdRepository($tableName);
        }

        if ($rulesNamaspace != null) {
            $this->rules = new $rulesNamaspace();
        }
    }
}

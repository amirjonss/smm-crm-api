<?php

namespace App\Entity\Traits;

trait CreatedUpdatedDeletedAtAndByTrait
{
    use CreatedAtAndByAccessorsTrait;
    use DeletedAtAndByAccessorsTrait;
    use UpdatedAtAndByAccessorsTrait;
}

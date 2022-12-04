<?php

namespace App\Repositories\Permission;

use App\Models\Permission;
use App\Repositories\Base\BaseRepository;

class PermissionRepository extends BaseRepository implements PermissionRepositoryInterface
{
    public function __construct(Permission $model)
    {
        parent::__construct($model);
    }
}
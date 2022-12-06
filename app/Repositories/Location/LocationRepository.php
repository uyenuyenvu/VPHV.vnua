<?php

namespace App\Repositories\Location;

use App\Models\Location;
use App\Repositories\Base\BaseRepository;

class LocationRepository extends BaseRepository implements LocationRepositoryInterface
{
    public function __construct(Location $model)
    {
        parent::__construct($model);
    }

}

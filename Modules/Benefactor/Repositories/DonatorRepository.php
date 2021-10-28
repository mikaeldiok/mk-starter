<?php

namespace Modules\Benefactor\Repositories;

use App\Repositories\BaseRepository;
use Modules\Benefactor\Repositories\Contracts\DonatorRepositoryInterface;
use Modules\Benefactor\Entities\Donator;

/** @property Donator $model */
class DonatorRepository extends BaseRepository implements DonatorRepositoryInterface
{
    public function model()
    {
        return Donator::class;
    }

    public function getDonatorsByDonatorQuery($query, $donator_id){
        return $query->where('id', $donator_id)->withTrashed();
    }
}

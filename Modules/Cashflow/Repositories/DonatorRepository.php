<?php

namespace Modules\Cashflow\Repositories;

use App\Repositories\BaseRepository;
use Modules\Cashflow\Repositories\Contracts\DonationRepositoryInterface;
use Modules\Cashflow\Entities\Donation;

/** @property Donation $model */
class DonationRepository extends BaseRepository implements DonationRepositoryInterface
{
    public function model()
    {
        return Donation::class;
    }

    public function getDonationsByDonationQuery($query, $Donation_id){
        return $query->where('id', $Donation_id)->withTrashed();
    }
}

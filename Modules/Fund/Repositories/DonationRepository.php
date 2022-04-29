<?php

namespace Modules\Fund\Repositories;

use App\Repositories\BaseRepository;
use Modules\Fund\Repositories\Contracts\DonationRepositoryInterface;
use Modules\Fund\Entities\Donation;

/** @property Donation $model */
class DonationRepository extends BaseRepository implements DonationRepositoryInterface
{
    public function model()
    {
        return Donation::class;
    }

    public function getDonationsByDonationQuery($query, $donation_id){
        return $query->where('id', $donation_id)->withTrashed();
    }
}

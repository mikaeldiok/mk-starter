<?php

namespace Modules\Benefactor\Repositories;

use App\Repositories\BaseRepository;
use Modules\Benefactor\Repositories\Contracts\CommitmentRepositoryInterface;
use Modules\Benefactor\Entities\Commitment;

/** @property Commitment $model */
class CommitmentRepository extends BaseRepository implements CommitmentRepositoryInterface
{
    public function model()
    {
        return Commitment::class;
    }

    public function getCommitmentsByCommitmentQuery($query, $commitment_id){
        return $query->where('id', $commitment_id)->withTrashed();
    }
}

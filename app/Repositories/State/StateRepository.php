<?php

namespace App\Repositories\State;

use App\Models\State;

class StateRepository implements StateRepositoryInterface
{
    public function getState($id)
    {
        return State::find($id);
    }
}

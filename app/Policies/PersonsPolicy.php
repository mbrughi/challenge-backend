<?php

namespace App\Policies;

use App\Models\Persons;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class PersonsPolicy
{

    use HandlesAuthorization;

/*    public function modify(User $user, Persons $persons): Response
    {
        return $user->id === $persons->user_id
            ? Response::allow()
            : Response::deny('Non hai le autorizzazioni per cancellare questa persona');
    }
*/
    public function viewAny(User $user)
    {
        return in_array($user->role, ['admin', 'editor', 'viewer']);
    }

    public function view(User $user, Persons $person)
    {
        return in_array($user->role, ['admin', 'editor', 'viewer']);
    }

    public function create(User $user)
    {
        return in_array($user->role, ['admin', 'editor']);
    }

    public function update(User $user, Persons $person)
    {
        return in_array($user->role, ['admin', 'editor']);
    }

    public function delete(User $user, Persons $person)
    {
        return in_array($user->role, ['admin', 'editor']);
    }

}

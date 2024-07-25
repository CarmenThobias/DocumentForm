<?php

namespace App\Policies;

use App\Models\Document;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DocumentPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Document $document)
    {
        return in_array($user->role->title, ['Admin', 'Secretary', 'Staff']);
    }

    public function create(User $user)
    {
        return in_array($user->role->title, ['Admin', 'Secretary']);
    }

    public function delete(User $user, Document $document)
    {
        return $user->role->title === 'Admin';
    }
}

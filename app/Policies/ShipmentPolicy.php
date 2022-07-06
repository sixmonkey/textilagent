<?php

namespace App\Policies;

use App\Models\Shipment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ShipmentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return Response|bool
     */
    public function viewAny(User $user): Response|bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Shipment $shipment
     * @return bool
     */
    public function view(User $user, Shipment $shipment): bool
    {
        return $user->admin || $shipment->orders()->where('agent_id', $user->id);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return (bool)$user->admin;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Shipment $shipment
     * @return Response|bool
     */
    public function update(User $user, Shipment $shipment): Response|bool
    {
        return (bool)$user->admin;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Shipment $shipment
     * @return Response|bool
     */
    public function delete(User $user, Shipment $shipment): Response|bool
    {
        return (bool)$user->admin;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param Shipment $shipment
     * @return Response|bool
     */
    public function restore(User $user, Shipment $shipment): Response|bool
    {
        return (bool)$user->admin;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param Shipment $shipment
     * @return Response|bool
     */
    public function forceDelete(User $user, Shipment $shipment): Response|bool
    {
        return (bool)$user->admin;
    }
}

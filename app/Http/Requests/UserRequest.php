<?php

namespace App\Http\Requests;

class UserRequest extends Request
{
    /**
     * If a user is not permissioned to update roles we'll remove this bit from the request.
     *
     * @param array $attributes The request attributes
     *
     * @return array The filtered attributes
     */
    protected function filterEditRoles($attributes)
    {
        if (!auth()->user()->hasRole('admin') and isset($attributes['role_id'])) {
            unset($attributes['role_id']);
        }

        return $attributes;
    }

    protected $baseRules = [
        'telegram_id'       => 'alpha_dash|max:255',
        'is_shop_manager'   => 'boolean',
    ];
}

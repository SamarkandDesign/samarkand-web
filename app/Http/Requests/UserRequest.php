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
        if (!$this->user()->hasRole('admin')) {
            $attributes = $this->except($this->user()->adminFields);
        }

        return $attributes;
    }

    protected $baseRules = [
        'telegram_id'       => 'alpha_dash|max:255',
        'is_shop_manager'   => 'boolean',
    ];
}

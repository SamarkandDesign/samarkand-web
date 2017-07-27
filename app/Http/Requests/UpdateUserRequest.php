<?php

namespace App\Http\Requests;

class UpdateUserRequest extends UserRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $currentUser = $this->user();

        return $this->route('user')->id == $currentUser->id or $currentUser->hasRole('admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->sanitize();

        $user = $this->route('user');

        return array_merge($this->baseRules, [
            'username'    => 'max:255|unique:users,username,'.$user->id,
            'email'       => 'email|max:255|unique:users,email,'.$user->id,
            'password'    => 'confirmed|min:6',
            'telegram_id' => 'alpha_dash|max:255',
        ]);
    }

    /**
     * Sanitize the input for the request.
     */
    public function sanitize()
    {
        $attributes = $this->all();
        $attributes = $this->filterEditRoles($attributes);

        if (! strlen($this->get('password'))) {
            unset($attributes['password']);
        }

        $this->replace($attributes);
    }
}

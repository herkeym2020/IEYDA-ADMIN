<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at,
            'profile_photo' => $this->profile_photo ? \Storage::url($this->profile_photo) : null,
            'phone' => $this->phone,
            'address' => $this->address,
            'bio' => $this->bio,
            'role' => $this->role,
            'google2fa_enabled' => (bool) $this->google2fa_secret,
            'google2fa_recovery_codes' => $this->google2fa_recovery_codes ? json_decode($this->google2fa_recovery_codes) : [],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

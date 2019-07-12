<?php

namespace App\Domain\User\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Class User
 *
 * @property string name
 * @property string created_at
 * @property string updated_at
 *
 * @package App\Domain\User\Model
 */
class User extends Model
{
    public const FIELD_NAME = 'name';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    /**
     * @var bool
     */
    public $timestamps = true;

    /* --------------------------------------------------------------------------------
     * Setters/Getters
     * -------------------------------------------------------------------------------- */

    /**
     * @return string
     */
    public function getNameAttribute(): string
    {
        return $this->attributes[self::FIELD_NAME];
    }

    /**
     * @param string $name
     */
    public function setNameAttribute(string $name): void
    {
        $this->attributes[self::FIELD_NAME] = $name;
    }
}

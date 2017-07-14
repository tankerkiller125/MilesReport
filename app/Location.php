<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Location
 *
 * @property int $id
 * @property string $name
 * @property string $address
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Entry $entryFrom
 * @property-read \App\Entry $entryTo
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Location whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Location whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Location whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Location whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Location whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Location extends Model
{
    public function entryFrom() {
        return $this->belongsTo('App\Entry', 'from', 'id');
    }

    public function entryTo() {
        return $this->belongsTo('App\Entry', 'to', 'id');
    }
}

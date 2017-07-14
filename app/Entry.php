<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Entry
 *
 * @property int $id Entry ID
 * @property int $user_id User ID
 * @property int $from From location ID
 * @property int $to To location ID
 * @property float $distance Miles traveled
 * @property int $time Time traveled
 * @property int|null $mpg Miles per gallon during trip
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Location $fromLocation
 * @property-read \App\Location $toLocation
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entry whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entry whereDistance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entry whereFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entry whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entry whereMpg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entry whereTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entry whereTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entry whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entry whereUserId($value)
 * @mixin \Eloquent
 */
class Entry extends Model
{
    public $fillable = ['from', 'to', 'mpg', 'user_id', 'distance', 'time'];

    public function fromLocation()
    {
        return $this->hasOne('App\Location', 'id', 'from');
    }

    public function toLocation()
    {
        return $this->hasOne('App\Location', 'id', 'to');
    }
}

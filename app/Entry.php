<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Entry
 *
 * @property int $id Entry ID
 * @property int $userId User ID
 * @property int $from From location ID
 * @property int $to To location ID
 * @property int $miles Miles traveled
 * @property int $time Time traveled
 * @property int $mpg Miles per gallon during trip
 * @property \Carbon\Carbon $createdAt
 * @property \Carbon\Carbon $updatedAt
 * @method static \Illuminate\Database\Query\Builder|\App\Entry whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entry whereFrom($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entry whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entry whereMiles($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entry whereMpg($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entry whereTime($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entry whereTo($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entry whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entry whereUserId($value)
 * @mixin \Eloquent
 */
class Entry extends Model
{
    public $fillable = ['from', 'to', 'mpg', 'user_id', 'distance', 'time'];

    public function fromLocation() {
        return $this->hasOne('App\Location', 'id', 'from');
    }

    public function toLocation() {
        return $this->hasOne('App\Location', 'id', 'to');
    }
}

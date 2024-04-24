<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstrumentSupported extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'instruments_supported';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'isin',
        'wkn',
        'instrument_type_id',
        'name',
        'created_by',
        'updated_by',
    ];


    /**
     * Get the instrument type
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function instrumentType()
    {
        return $this->belongsTo('App\Models\InstrumentType');
    }

}

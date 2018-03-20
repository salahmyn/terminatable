<?php

namespace Salahmyn\Terminatable;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Salahmyn\Terminatable\Exception\FieldsNotDefinedException;

trait Terminatable
{
    private $starting_field = 'started_at';
    private $ending_field = 'ended_at';

    public function __construct()
    {
        if(isset($this->ending_date_field)){
            $this->ending_field = $this->ending_date_field;
        }
        if(isset($this->starting_date_field)){
            $this->starting_field = $this->starting_date_field;
        }
    }

    // get current
    public function scopeCurrentRecords(Builder $query)
    {
        return $query->where($this->ending_field , null)
                     ->orWhere($this->ending_field , '>' , date('Y-m-d'));
    }    

    public static function current()
    {
        return static::currentRecords()->first();
    }
    // get archive

    public function scopeArchive(Builder $query)
    {
        return $query->where($this->ending_field , '!=' ,  null)->get();
    }    

    // terminate (at time)

    public function terminate($date)
    {
        $current = $this->current();
        if($current){
            $current->{$this->ending_field} = $date;
            $current->save();
        }
        return $current;
    }
    


    // create ( terminate current and create new)
}

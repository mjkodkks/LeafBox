<?php

// namespace Library;

// use Eloquent;

class CD extends Eloquent {
    
    // protected $connection = 'mysql2-library';
    
    protected $table = 'cd';

    public $timestamps = false;

    public function book()   { return $this->belongsTo('Book'); }
    public function detail()   { return $this->hasMany('cddetail'); }
    public function borrow()   { return $this->hasOne('Library\Cdborrow', 'parent_id'); }
    
    //Relation
    // public function owner()   { return $this->belongsTo('User', 'id'); }
    
}

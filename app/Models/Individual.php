<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Individual extends Model
{
    protected $fillable = ['first_name', 'last_name', 'address', 'city', 'postal_code', 'country', 'date_of_birth', 'personal_description'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->initializeAttributes();
    }

    private function initializeAttributes()
    {
        $this->name = trim($this->first_name) . " " . trim($this->last_name);

        $d1 = new \DateTime(date('Y-m-d', strtotime('today')));
        try {
            $d2 = new \DateTime(date('Y-m-d', strtotime($this['date_of_birth'])));
        } catch (\Exception $e) {
            throw new \Exception("Incorrect date format for date_of_birth on record " . $this->name);
        }

        $this->age = $d1->diff($d2)->y;
    }
}

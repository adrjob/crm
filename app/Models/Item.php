<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'name',
        'sku',
        'sale_price',
        'purchase_price',
        'tax',
        'category',
        'unit',
        'type',
        'description',
        'created_by',
    ];


    public function units()
    {
        return $this->hasOne('App\Models\Unit', 'id', 'unit');
    }

    public function categories()
    {
        return $this->hasOne('App\Models\Category', 'id', 'category');
    }

    public function tax($taxes)
    {
        $taxArr = explode(',', $taxes);

        $taxes = [];
        foreach($taxArr as $tax)
        {
            $taxes[] = TaxRate::find($tax);
        }

        return $taxes;
    }

    public function taxRate($taxes)
    {
        $taxArr  = explode(',', $taxes);
        $taxRate = 0;
        foreach($taxArr as $tax)
        {
            $tax     = TaxRate::find($tax);
            $taxRate += $tax->rate;
        }

        return $taxRate;
    }

    public function taxes()
    {
        return $this->hasOne('App\Models\TaxRate', 'id', 'tax');
    }

    public static function itemData($taxes)
    {
        $taxArr = explode(',', $taxes);

        $taxes = [];
        foreach($taxArr as $tax)
        {
            $taxes[] = TaxRate::find($tax);
        }

        return $taxes;
    }

    public static function unit($unit_id)
    {
        $unitArr  = explode(',', $unit_id);
        $unitRate = 0;
        foreach($unitArr as $unit)
        {
            $unit     = Unit::find($unit_id);
            $unitRate = $unit->name;
           
        }

        return $unitRate;
    }

    public static function category($category)
    {
        $categoryArr  = explode(',', $category);
        $unitRate = 0;
        foreach($categoryArr as $category)
        {
            $category     = Category::find($category);
            $unitRate = $category->name;
        }

        return $unitRate;
    }
    

    public static function taxs($taxes)
    {
       
        $taxArr = explode(',', $taxes);
        $taxes = 0;
        foreach($taxArr as $tax)
        {
            $tax = TaxRate::find($tax);
            $taxes = $tax->name;
        }

        return $taxes;
    }
}

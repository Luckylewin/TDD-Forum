<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2019/1/5
 * Time: 20:01
 */

namespace App\Rules;

use App\Inspections\Spam;

class SpamFree
{
    public function passes($attribute,$value)
    {
        try {
            return ! resolve(Spam::class)->detect($value);
        } catch (\Exception $e) {
            return false;
        }
    }

}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Spam extends Model
{
    public function detect($body)
    {
        $this->detectInvalidKeywords($body);

        return false;
    }

    public function detectInvalidKeywords($body)
    {
        $invalidKeywords = [
            'something forbidden'
        ];

        foreach ($invalidKeywords as $invalidKeyword) {
            if (stripos($body, $invalidKeyword) !== false) {
                throw new \Exception('Your reply contains spam.');
            }
        }
    }
}

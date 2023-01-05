<?php

namespace App\Helpers;

class Helper
{

    public static function monthMapper($month): int
    {

        if ($month <= 0) {

            return ($month - 1) + 12;

        }

        return ($month - 1);

    }

    public static function yearMapper($month, $this_year): int
    {

        if ($month <= 0) {

            return $this_year - 1;

        }

        return $this_year;

    }

}

<?php
/**
 * Created by PhpStorm.
 * User: Sahand
 * Date: 2/14/19
 * Time: 12:48 AM
 */

namespace App\Classes;


use App\Jobs\TestJob;

class CallerId
{

    public function readFile(){

        TestJob::dispatch();

        return 'job read';
    }
}
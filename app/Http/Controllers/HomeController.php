<?php

namespace App\Http\Controllers;

use App\Models\Ship;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Core\Exceptions\SystemException;
use App\Exceptions\TestException;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $re)
    {
    	return [1,2,3,4];
    }
}

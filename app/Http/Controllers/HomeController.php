<?php

namespace App\Http\Controllers;

use App\Jobs\WriteSocialUserCredentials;
use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Models\Home;

class HomeController extends Controller
{
    private $links = [
        [ 'url'=>'https://laravel.com/docs' , 'name' =>'Docs'],
        [ 'url'=>'https://laracasts.com' ,'name'=>'Laracasts'],
        [ 'url'=>'https://laravel-news.com' , 'name' =>'News'],
        [ 'url'=>'https://blog.laravel.com' , 'name' =>'Blog'],
        [ 'url'=>'https://nova.laravel.com' , 'name' =>'Nova'],
        [ 'url'=>'https://forge.laravel.com' , 'name' =>'Forge'],
        [ 'url'=>'https://github.com/laravel/laravel' , 'name' =>'GitHub']
    ];
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function links()
    {
       return response()->json(['links'=>$this->links],Response::HTTP_OK);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Link;
use App\Models\AccessLog;

class ShortLinkController extends Controller
{
    public function __construct()
    {}

    public function redirect(Request $request, $short_link) {
        $acessLog = new AccessLog();
        $acessLog->user_agent = $request->headers->get('user-agent');
        $acessLog->short = $short_link;
        $acessLog->ip = $request->ip();

        $taget = Link::where('short', $short_link);
        $acessLog->valid = $taget->exists();
        $acessLog->save();

        if($acessLog->valid){
            return redirect(
                Link::where('short', $short_link)->get()->last()->target
            );
        }

        return response('URL não encontrada', 404)
            ->header('Content-Type', 'text/plain');
    }
}

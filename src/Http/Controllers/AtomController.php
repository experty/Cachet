<?php

namespace CachetHQ\Cachet\Http\Controllers;

use CachetHQ\Cachet\Facades\Setting;
use CachetHQ\Cachet\Models\Incident;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;
use Roumen\Feed\Facades\Feed;

class AtomController extends Controller
{
    /**
     * Generates an Atom feed of all incidents.
     *
     * @return \Illuminate\Http\Response
     */
    public function feedAction()
    {
        $feed = Feed::make();
        $feed->title = Setting::get('app_name');
        $feed->description = trans('cachet.feed');
        $feed->link = Setting::get('app_domain');

        $feed->setDateFormat('datetime');

        Incident::all()->map(function ($incident) use ($feed) {
            $feed->add(
                $incident->name,
                Setting::get('app_name'),
                Setting::get('app_domain'),
                $incident->created_at->toAtomString(),
                $incident->message
            );
        });

        return $feed->render('atom');
    }
}

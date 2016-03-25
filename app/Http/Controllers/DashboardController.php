<?php
/**
 * This controller handles all actions related to the admin dashboard
 * the Snipe-IT Asset Management application.
 *
 * PHP version 5.5.9
 * @package    Snipe-IT
 * @version    v1.0
 */
namespace App\Http\Controllers;

use App\Http\Controllers\AdminController;
use App\Models\Actionlog;
use View;
use Auth;
use Redirect;
use App\Models\Asset;
use App\Models\Company;

class DashboardController extends Controller
{
    /**
     * Show the administration dashboard page.
     *
     * @return View
     */
    public function getIndex()
    {
        // Show the page
        if (Auth::user()->hasAccess('admin')) {

            $recent_activity = Actionlog::orderBy('created_at', 'DESC')
                ->with('accessorylog', 'consumablelog', 'licenselog', 'assetlog', 'adminlog', 'userlog')
                ->take(7)
                ->get();


            $asset_stats['total'] = Asset::Hardware()->count();

            $asset_stats['rtd']['total'] = Asset::Hardware()->RTD()->count();

            if ($asset_stats['rtd']['total'] > 0) {
                $asset_stats['rtd']['percent'] = round(($asset_stats['rtd']['total']/$asset_stats['total']) * 100);
            } else {
                $asset_stats['rtd']['percent'] = 0;
            }


            $asset_stats['pending']['total'] = Asset::Hardware()->Pending()->count();

            if ($asset_stats['pending']['total'] > 0) {
                $asset_stats['pending']['percent'] = round(($asset_stats['pending']['total']/$asset_stats['total']) * 100);
            } else {
                $asset_stats['pending']['percent'] = 0;
            }


            $asset_stats['deployed']['total'] = Asset::Hardware()->Deployed()->count();

            if ($asset_stats['deployed']['total'] > 0) {
                 $asset_stats['deployed']['percent'] = round(($asset_stats['deployed']['total']/$asset_stats['total']) * 100);
            } else {
                $asset_stats['deployed']['percent'] = 0;
            }


            $asset_stats['undeployable']['total'] = Asset::Hardware()->Undeployable()->count();

            if ($asset_stats['undeployable']['total'] > 0) {
                $asset_stats['undeployable']['percent'] = round(($asset_stats['undeployable']['total']/$asset_stats['total']) * 100);
            } else {
                $asset_stats['undeployable']['percent'] = 0;
            }

            $asset_stats['archived']['total'] = Asset::Hardware()->Archived()->count();

            if ($asset_stats['archived']['total'] > 0) {
                $asset_stats['archived']['percent'] = round(($asset_stats['archived']['total']/$asset_stats['total']) * 100);
            } else {
                $asset_stats['archived']['percent'] = 0;
            }


            return View::make('dashboard')->with('asset_stats', $asset_stats)->with('recent_activity', $recent_activity);
        } else {
        // Redirect to the profile page
            return Redirect::route('view-assets');
        }
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: ryan
 * Date: 16/09/2017
 * Time: 6:44 PM
 */

namespace BUP\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\View\View;

class RepairController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function index()
  {
    return view('repair');
  }

  // 2+3RFR
  public function calc_fridge(Request $r)
  {
    if ($r->webgo == "") {
      return "No webgo.";     
    }

    if ($r->gp == "") {
      return "No GP.";
    }

    if ($r->inc == "") {
      return "No increment.";
    }

    // Init vars.
    $webgo = (double)$r->webgo;
    $gp = (double)$r->gp / 100;
    $cost = $webgo - ($webgo * $gp);

    $BUP_GP = 0;

    $unit_prices = array();
    $bup_prices = array();
    $total_prices = array();

    $item_profits = array();
    $bup_profits = array();

    $wg_diff = array();
    $deal = array();

    // Calc BUP prices.
    for ($i = $webgo; $i >= $cost; $i -= (double)$r->inc) { 
      $plan = $r->plan;
      array_push($unit_prices, $i);
      $bup = 0;

      // Select BUP GP and Cost.
      if (isset($plan)) {
        $costs = $this->rfr_bup_cost($i, $plan);
        $bup = $costs[0];
        $BUP_GP = $costs[1];
      } else {
        return "Plan not selected";
      }

      array_push($bup_prices, $bup);
      $price = $i + $bup;
      array_push($total_prices, $price);
      array_push($item_profits, $i - $cost);
      array_push($bup_profits, $bup * $BUP_GP);

      if ($price > $webgo) {
        array_push($deal, 0);
      } else {
        array_push($deal, 1);
      }
    }

    return view('results_2rpl', ['unit_prices' => $unit_prices,
      'total_prices' => $total_prices,
      'bup_prices' => $bup_prices,
      'item_profits' => $item_profits,
      'bup_profits' => $bup_profits,
      'deal' => $deal,
      'webgo' => $webgo,
      'cost' => $cost]);
  }

  public function rfr_bup_cost($i, $plan)
  {
    $bup = 0;
    $BUP_GP = 0;

    if ($i <= 500) {
      if ($plan == '2plus3' || $plan == '3plus3') {
        $bup = 89;
        $BUP_GP = 0.5;
      } elseif($plan == '2plus4' || $plan == '1plus4') {
        $bup = 99;
        $BUP_GP = 0.5;
      }
    } elseif ($i > 500 && $i <= 1000) {
      if ($plan == '2plus3') {
        $bup = 99;
        $BUP_GP = 0.5;
      } elseif($plan == '2plus4' || $plan == '1plus4') {
        $bup = 119;
        $BUP_GP = 0.5;
      } elseif ($plan == '3plus3') {
        $bup = 109;
        $BUP_GP = 1.5;
      }
    } elseif ($i > 1000 && $i <= 2000) {
      if ($plan == '2plus3') {
        $bup = 119;
        $BUP_GP = 0.5;
      } elseif($plan == '2plus4' || $plan == '1plus4') {
        $bup = 154;
        $BUP_GP = 0.5;
      } elseif ($plan == '3plus3') {
        $bup = 139;
        $BUP_GP = 1.5;
      }
    } elseif ($i > 2000 && $i <= 3000) {
      if ($plan == '2plus3') {
        $bup = 125;
        $BUP_GP = 0.5;
      } elseif($plan == '2plus4' || $plan == '1plus4') {
        $bup = 179;
        $BUP_GP = 0.5;
      } elseif ($plan == '3plus3') {
        $bup = 159;
        $BUP_GP = 1.5;
      }
    } elseif ($i > 3000 && $i <= 5000) {
      if ($plan == '2plus3') {
        $bup = 159;
        $BUP_GP = 0.5;
      } elseif($plan == '2plus4' || $plan == '1plus4') {
        $bup = 209;
        $BUP_GP = 0.5;
      } elseif ($plan == '3plus3') {
        $bup = 189;
        $BUP_GP = 1.5;
      }
    } elseif ($i > 5000 && $i <= 10000) {
      if ($plan == '2plus3') {
        $bup = 279;
        $BUP_GP = 0.5;
      } elseif($plan == '2plus4' || $plan == '1plus4') {
        $bup = 319;
        $BUP_GP = 0.5;
      } elseif ($plan == '3plus3') {
        return "Play not available";
      }
    } else {
      return "No BUP for you :'(";
    }
    return array($bup, $BUP_GP);
  }

  public function bup_cost($webgo)
  {
    if ($webgo <= 500 && $webgo > 450.01) {
      return 75;
    } elseif ($webgo <= 450 && $webgo > 400) {
      return 67.50;
    } elseif ($webgo <= 400 && $webgo > 350) {
      return 60;
    } elseif ($webgo <= 350 && $webgo > 300) {
      return 52.50;
    } elseif ($webgo <= 300 && $webgo > 250) {
      return 45;
    } elseif ($webgo <= 250 && $webgo > 200) {
      return 37.50;
    } elseif ($webgo <= 200 && $webgo > 175) {
      return 30;
    } elseif ($webgo <= 175 && $webgo > 150) {
      return 26.25; 
    } elseif ($webgo <= 150 && $webgo >= 0) {
      return 22.5;
    } else {
      // ERROR
    }

    return 0;
  } 

  public function calc_manual(Request $r)
  {
    // No webgo
    if ($r->webgo == "") {
      return "No webgo.";
    }

    // No gp
    if ($r->gp == "") {
      return "No gp.";
    }

    // No bup
    if ($r->bup == "") {
      return "No bup.";
    }

    $webgo = (double)$r->webgo;
    $gp = (double)$r->gp / 100;
    $bup = (double)$r->bup;
    $cost = $webgo - ($webgo * $gp);

    $unit_prices = array();
    $total_prices = array();
    $deal = array();

    for ($i = $webgo; $i >= $cost; $i -= 10) {
      array_push($unit_prices, $i);
      $price = $i + $bup;
      array_push($total_prices, $price);

      if ($price > $webgo) {
        array_push($deal, 0);
      } else {
        array_push($deal, 1);
      }
    }

    return view('results', ['unit_prices' => $unit_prices,
      'total_prices' => $total_prices,
      'bup' => $bup,
      'deal' => $deal,
      'cost' => $cost,
      'webgo' => $webgo]);
  }

  public function calc_rpl(Request $r)
  {
     // Check all fields are filled out.
    if ($r->webgo == "") {
      return "No webgo.";     
    }

    if ($r->gp == "") {
      return "No GP.";
    }

    if ($r->inc == "") {
      return "No increment.";
    }

    // Init vars.
    $webgo = (double)$r->webgo;
    $gp = (double)$r->gp / 100;
    $cost = $webgo - ($webgo * $gp);

    $BUP_GP = 0;

    $unit_prices = array();
    $bup_prices = array();
    $total_prices = array();

    $item_profits = array();
    $bup_profits = array();

    $wg_diff = array();
    $deal = array();

    // Calculate BUP prices.
    for ($i = $webgo; $i >= $cost; $i -= (double)$r->inc) { 
      $cat = $r->category;
      array_push($unit_prices, $i);
      $bup = 0;
      // Select BUP GP and Cost.
      if (isset($cat)) {
        if ($i <= 500) {
          $cat = 'rpl';
        }
        switch ($cat) {
          case 'rpl':
            $BUP_GP = 0.4188;
            $bup = $this->bup_cost($i);
            break;
          case 'computer':
            if ($i <= 1000 && $i > 750) {
              $bup = 159;
              $BUP_GP = 0.45;
            } else {
              $bup = 119;
              $BUP_GP = 0.3806;
            }
            break;
          case 'tablet':
              $bup = 119;
              $BUP_GP = 0.3950;
            break;
          case 'other':
              $bup = 119;
              $BUP_GP = 0.4268;
            break;
          default:
            return "ERR: No category set.";
            break;
        }
      } else { return "ERR: No category set."; }

      array_push($bup_prices, $bup);
      $price = $i + $bup;
      array_push($total_prices, $price);
      array_push($item_profits, $i - $cost);
      array_push($bup_profits, $bup * $BUP_GP);

      if ($price > $webgo) {
        array_push($deal, 0);
      } else {
        array_push($deal, 1);
      }
    }

    return view('results_2rpl', ['unit_prices' => $unit_prices,
      'total_prices' => $total_prices,
      'bup_prices' => $bup_prices,
      'item_profits' => $item_profits,
      'bup_profits' => $bup_profits,
      'deal' => $deal,
      'webgo' => $webgo,
      'cost' => $cost]);
  }
}
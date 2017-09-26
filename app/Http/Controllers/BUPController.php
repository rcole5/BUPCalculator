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

class BUPController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
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
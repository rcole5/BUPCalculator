@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Results</div>

                    <div class="panel-body">
                        <table class="table table-bordered table-hover" style="text-align: center">
												<tr>
												  <th style="text-align: center">Unit</th>
                          <th style="text-align: center">BUP</th>
													<th style="text-align: center">Total</th>
													<th style="text-align: center">Webgo Diff</th>
													<th style="text-align: center">Item Profit</th>
                          <th style="text-align: center">Total Profit</th>
												</tr>
                           @for ( $i = 0; $i < count($unit_prices); $i++ )
                               @if ($bup_profits[$i] + $item_profits[$i] >= $item_profits[0])
                                    <tr class="success">
                                @else
                                   <tr>
                                @endif
                                   <td>${{ number_format($unit_prices[$i], 2) }}</td>
                                   <td>${{ number_format($bup_prices[$i], 2) }}
                                   <td>${{ number_format($total_prices[$i], 2) }}</td>
                                   <td>${{ number_format($total_prices[$i] - $webgo, 2) }}</td>
																	 <td>${{ number_format($item_profits[$i], 2) }}</td>
                                   <td>${{ number_format($item_profits[$i] + $bup_profits[$i], 2)}}
                               </tr>
                           @endfor
                        </table>
                        Webgo: ${!! number_format($webgo, 2) !!} <br>
                        Cost: ${!! number_format($cost, 2) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Results</div>

                    <div class="panel-body">
                        <table class="table">
												<tr>
												  <th>Unit + BUP</th>
													<th>Total</th>
													<th>Webgo Diff</th>
													<th>Above Cost</th>
												</tr>
                           @for ( $i = 0; $i < count($unit_prices); $i++ )
                               @if ((int)$deal[$i] == 1)
                                    <tr style="background-color: #ccffd0">
                                @else
                                   <tr>
                                @endif
                                   <td class="col-md-3">${{ $unit_prices[$i] }} + ${{ $bup }}</td>
                                   <td class="col-md-3">${{ $total_prices[$i] }}</td>
                                   <td class="col-md-3">${{ $total_prices[$i] - $webgo }}</td>
																	 <td class="col-md-3">${{ $unit_prices[$i] - $cost }}</td>
                               </tr>
                           @endfor
                        </table>
                        Webgo: ${!! $webgo !!} <br>
												BUP: ${!! $bup !!} <br>
                        Cost: ${!! $cost !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

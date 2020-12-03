<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\despesa;

class DashboardController extends Controller
{
    public function index(Request $request){
        
        $userId = Auth::id();

        $request->validate([
            'valueFrom' => 'bail|nullable|regex:/^.{2}\d{1,6}\,\d{2}$/',
            'valueAt' => 'bail|nullable|regex:/^.{2}\d{1,6}\,\d{2}$/',
            'dateFrom' => 'bail|nullable|date_format:d/m/Y',
            'dateAt' => 'bail|nullable|date_format:d/m/Y',
        ]);

        $despesas = despesa::where('user_id', '=', $userId);

        $filterOptions = [];
        if(@$request->dateFrom){
            $filterOptions['dateFrom'] = $request->dateFrom;
            $date = explode('/', $request->dateFrom);
            $date = $date[2].'/'.$date[1].'/'.$date[0];
            $despesas->where('date', '>=', $date);
        }
        if(@$request->dateAt){
            $filterOptions['dateAt'] = $request->dateAt;
            $date = explode('/', $request->dateAt);
            $date = $date[2].'/'.$date[1].'/'.$date[0];
            $despesas->where('date', '<=', $date);
        }

        if(@$request->valueFrom){
            $filterOptions['valueFrom'] = $request->valueFrom;
            $despesas->where('value', '>=', str_replace(',', '.', str_replace('R$', '', $request->valueFrom)));
        }

        if(@$request->valueAt){
            $filterOptions['valueAt'] = $request->valueAt;
            $despesas->where('value', '<=', str_replace(',', '.', str_replace('R$', '', $request->valueAt)));
        }
        

        if(@$request->order){
            $filterOptions['order'] = $request->order;
            $order = $request->order;

            $order == "rec" || $order == "ant" ? $field = 'date' : $field = 'value';
            $order == "rec" || $order == "mav" ? $queryOrder = 'desc' : $queryOrder = 'asc';
            
            $despesas = $despesas->orderby($field, $queryOrder);
        }else{
            $order = null;
            $despesas = $despesas->orderby('date', 'desc');
        }

        $despesas = $despesas->get();

        foreach($despesas as $despesa){
            $newDate = explode('-', $despesa->date);
            $despesa->date = $newDate[2].'/'.$newDate[1].'/'.$newDate[0];
        }


        

        $currentDate = date("Y-m");
        $MonthDespesa = despesa::where('user_id', '=', $userId)->where('date', '>=', $currentDate.'-01')->where('date', '<=', $currentDate.'-31');
        
        $highest = $MonthDespesa->max('value');
        $lowest = $MonthDespesa->min('value');
        $sum = $MonthDespesa->sum('value');
        $average = round($MonthDespesa->average('value'), 2);
        $infos = [
            'highest' => $highest == null ? '0,00' : str_replace('.', ',', $highest),
            'lowest' => $lowest == null ? '0,00' : str_replace('.', ',', $lowest), 
            'sum' => $sum == null ? '0,00' : str_replace('.', ',', $sum), 
            'average' => $average == null ? '0,00' : str_replace('.', ',', $average)
        ];




        
        $chartValues = despesa::where('user_id', '=', $userId)->orderby('date', 'desc')->select('value', 'date')->limit(10)->get();
        $amount = $chartValues->count();
        $values = [];
        $dates = [];
        foreach($chartValues as $chartValue){
            array_push($values, (float) $chartValue->value);
            array_push($dates, explode('-', $chartValue->date)[2].'/'.explode('-', $chartValue->date)[1]);
        }
        $values = array_reverse($values);
        $dates = array_reverse($dates);

        $chart = ['values' => $values, 'dates' => $dates, 'amount' => $amount];
        
        

        return view('dashboard.index', ['despesas' => $despesas, 'infos' => $infos, 'chart' => $chart, 'filterOptions' => $filterOptions, 'order' => $order]);
    }

    public function auth(){
        if(Auth::check()){
            return redirect()->route('dashboard.index');
        }else{
            return redirect()->route('user.login');
        }
    }
}

<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Expense,App\ExpenseCategory,Validator,DB;

class ExpenseController extends Controller
{
    public $successStatus = 200;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){

        $f_date = (isset($request->from_date) && !empty($request->from_date))?$request->from_date:date('Y-m-d');
        $t_date = (isset($request->to_date) && !empty($request->to_date))?$request->to_date:date('Y-m-d');

        $categroy_id = (isset($request->expense_category) && !empty($request->expense_category))?$request->expense_category:'';


        if(!empty($request->from_date)){
            if($categroy_id!=''){
                $expenses = Expense::whereBetween('date', [$f_date, $t_date])->where('expense_category_id',$categroy_id)->orderby('id','DESC')->get();
            }else{
                $expenses = Expense::whereBetween('date', [$f_date, $t_date])->orderby('id','DESC')->get();
            }
        }else{
            if($categroy_id!=''){
                $expenses = Expense::where('expense_category_id',$categroy_id)->orderby('id','DESC')->get();
            }else{
                $expenses = Expense::orderby('id','DESC')->get();
            }
        }

        $monthlyexpenses = DB::select("SELECT expenses.expense_category_id,SUM(amount) as total_amount FROM `expenses` WHERE MONTH(date) = ".date('m')." GROUP BY `expenses`.`expense_category_id`");
        $total_expense = 0;
        foreach ($monthlyexpenses as $keys => $monthlyexpense) {
            $dt = ExpenseCategory::find($monthlyexpense->expense_category_id);
            $monthlyexpense->category_name = (!empty($dt))?$dt->name:'';
            $monthlyexpense->expense_amount = number_format($monthlyexpense->total_amount,2);
            $total_expense += $monthlyexpense->total_amount;
        }


        if(!$expenses->isEmpty()){
            $total_expenses = 0;
            foreach ($expenses as $keys => $expense) {
                $expense->category;
                $expense->user;
                $total_expenses += ($expense->amount_after_tax!='0.00')?$expense->amount_after_tax:$expense->amount;
            }
            $data['expense'] = $expenses;
            $data['final_expenses'] = number_format($total_expenses,2);
            $data['month']['expense'] = $monthlyexpenses;
            $data['msg'] = 'Salesman Expenses List';
            $data['month']['total'] = number_format($total_expense,2);
            $data['status'] = true;
            $status = $this-> successStatus;
        }else{
            $data['expense'] = [];
            $data['msg'] = 'No Expenses Found';
            $data['status'] = false;
            $status = 401;
        }
        return response()->json($data, $status); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'expense_category' => 'required',
            'date' => 'required',
            'amount' => 'required',
        ]);
        
        if ($validator->fails()) { 
            return response()->json($validator->errors(), 401);            
        }

        $expense = new Expense;
        $expense->name = $request->title;
        $expense->expense_category_id = $request->expense_category;
        $expense->date = $request->date;
        $expense->amount = $request->amount;
        $expense->amount_after_tax = 0;
        $expense->note = $request->remarks;
        $expense->payment_mode_id = 1;
        $expense->currency_id = 1;
        $expense->user_id = Auth::id();

        if($expense->save()){
            $data['msg'] = 'Expense Added successfully';
            $data['status'] = true;
            $status = $this-> successStatus;
        }else{
            $data['msg'] = 'Something went wrong';
            $data['status'] = fasle;
            $status = 401;
        }
        return response()->json($data, $status); 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function expenseCategoryList(Request $request){
        $list = ExpenseCategory::get();
        if($list){
            $data['category'] = $list;
            $data['msg'] = 'Expense Category List';
            $data['status'] = true;
            $status = $this-> successStatus;
        }else{
            $data['category'] = [];
            $data['msg'] = 'No Expense Category';
            $data['status'] = false;
            $status = 401;
        }
        return response()->json($data, $status); 
    }

    public function monthlyExpense(Request $request){
        $monthlyexpense = DB::select("SELECT expenses.expense_category_id,SUM(amount) as total_amount FROM `expenses` WHERE MONTH(date) = ".date('m')." GROUP BY `expenses`.`expense_category_id`");
        $total_expense = 0;
        foreach ($monthlyexpense as $keys => $task) {
            $dt = ExpenseCategory::find($task->expense_category_id);
            $task->category_name = (!empty($dt))?$dt->name:'';
            $task->expense_amount = number_format($task->total_amount,2);
            $total_expense += $task->total_amount;
        }

        if(!empty($monthlyexpense)){
            $data['expenses'] = $monthlyexpense;
            $data['total_expense'] = number_format($total_expense,2);
            $data['msg'] = 'Total Expense This Month';
            $data['status'] = true;
            $status = $this-> successStatus;
        }else{
            $data['expenses'] = [];
            $data['total_expense'] = [];
            $data['msg'] = 'Total Expense This Month';
            $data['status'] = true;
            $status = 401;
        }
        return response()->json($data, $status); 
    }

}

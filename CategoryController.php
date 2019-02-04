<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function insert(Request $request)
    {
    	$categoryname=$request->input('categoryname');
    	$categorytype=$request->input('categorytype');
  		$action=$request->input('action');
    	$userid=$request->input('userid');

    	if($action=='insert')
    	{
    	DB::insert('insert into category(category_name,category_type,category_user) values(?,?,?)',
    		[$categoryname,$categorytype,$userid]);
 
    	 return response()->json([
    	 		'responcecode' =>'200',
                'message' => 'Data Insert Successfully'
                ]);
    	}
    	
    	elseif($action=='delete')
    	{
	$show=DB::table('category')->where('category_user', $userid)->delete();
			return response()->json([
				'responcecode' => '200',
				'message' => "Data delete Successfully"
			]);
		}
		elseif($action=='update') {
			$show=DB::update('update category set category_name = ? where category_user=?',[$categoryname,$userid]);
			return response()->json([
				'responcecode' => '200',
				'message' =>'Data update Successfully'
			]);
		}
    	else
    	{
    		return response()->json([
    			'responcecode' =>'000',
    			'message' => 'No action found'
    		]);
    	}
    }
    public function categorylist(Request $request)
    {
        $userid=$request->input('userid');

        $show=DB::select('select * from category where category_user = ?',[$userid]);
        if(count($show))
        {
            return response()->json([
                'responcecode' =>'200',
                'message' => 'categorylist',
                'categorylist'=>$show
                //echo end($show)
            ]);
            //$data=array();
        //echo end($data);
            
            }
            else
            {
                return response()->json([
                    'responcecode' =>'000',
                    'message' => 'No record found'
                ]);
            }
        }
   
    }
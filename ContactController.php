<?php

namespace App\Http\Controllers;
                                                                                                                                                                                                                                
use Illuminate\Http\Request;
use DB;

class ContactController extends Controller
{
    public function addcontact(Request $request)
    {
      $user = $request->input('userid');
      $firstname = $request->input('firstname');
      $lastname = $request->input('lastname');
      $categoryid = $request->input('categoryid');
      $contactname = $request->input('contactname');
      $hair = $request->input('hair');
      $company = $request->  input('company');   
      $description = $request->input('description');
      $other = $request->input('other');
      $address = $request->input('address');
      $phonenumber = $request->input('phonenumber');

      
      DB::insert('insert into contacts(user_id,first_name,last_name,category_id,contact_name,hair,company,description,other,address,phone_number) values(?,?,?,?,?,?,?,?,?,?,?)',[$user,$firstname,$lastname,$categoryid,$contactname,$hair,$company,$description,$other,$address,$phonenumber]);

      return response()->json([
                'responcecode' => '200',
                'message' => 'Record Edit Successfully'
                ]);
    }

    public function editcontact(Request $request)
            {
               $userid = $request->input('userid');              
               $firstname = $request->input('firstname');
               $lastname = $request->input('lastname');
               $categoryid = $request->input('categoryid');
               $hair = $request->input('hair');
               $company = $request->input('company');
               $description = $request->input('description');
               $other = $request->input('other');
               $address = $request->input('address');
               $phonenumber = $request->input('phonenumber');
                
           
               $show = DB::update('update contacts set first_name=? , last_name=?  , category_id=? , hair=? , company=? , description=? , other=? , address=? , phone_number=?  where user_id=?',[$firstname,$lastname,$categoryid,$hair,$company,$description,$other,$address,$phonenumber,$userid]); 
             
                  // return response()->json([
               //'message' => 'Record Edit Successfully'
                //]); 
                   return response()->json([
                    'responcecode' => '200',
                    'message' => 'data edit Successfully', 
                    'editcontact' => $show

                  ]);
            }

    public function sendcontact(Request $request)
    {
        $userid = $request->input('userid');
        $user=DB::select('select * from contacts where user_id=?',[$userid]);
      
        if(count($user))
        { 
          return response()->json([
            'responcecode'=>'200',  
            'message' => 'data fetch Successfully',     
            'sendcontact' =>  $user
          ]); 
        }  
        else
        {
                return response()->json([
                  'responcecode' => '000',
               'message' => 'Record Not Found'
                ]); 
        }
      }
      public function search(Request $request)
      { 
        $value=$request->input('search');
      /*  $data=DB::select('select * from contacts where first_name=? or last_name=? or category_name=? or hair=? or company=? or description=? or other =? or address=? or phone_number=?',[$value,$value,$value,$value,$value,$value,$value,$value,$value]);
       */
      //$data=DB::select("select * from contacts WHERE first_name LIKE ?",["%$value%"]);
       $data=DB::select("select * from contacts WHERE first_name LIKE ? or last_name LIKE ? or category_name LIKE ? or hair LIKE ? or company LIKE ? or description LIKE ? or other LIKE ? or address LIKE ? or phone_number LIKE ?",["%$value%","%$value%","%$value%","%$value%","%$value%","%$value%","%$value%","%$value%","%$value%"]);
      
       if(count($data))
       {        
          return response()->json([
            'responcecode' => '200', 
            'message' => 'data found Success',
            'search' => $data
          ]);
      } 
      else    
      {       
           return response()->json([
             'responcecode' => '000',
             'message' => 'No Record Found'
           ]);
         }

    }
}
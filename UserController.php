<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;  

class UserController extends Controller
{
  public function signup(Request $request)
    {
        $firstName = $request->input('first_name');
        $lastName = $request->input('last_name');
        $email = $request->input('email');
        $password = $request->input('password');
        $phoneNumber = $request->input('phone_number');
        $category = $request->input('category');
        $socialid = $request->input('social_id');
        $token=sha1(time());

        $check=DB::select('select * from user where email=?',[$email]);
        if(count($check)){
            return response()->json([
               'message' => 'Already Register'
                ]);
        }
else{
        DB::insert('insert into user(first_name,last_name,email,password,phone_number,category,social_id,token) values(?,?,?,?,?,?,?,?)',
        [$firstName,$lastName,$email,$password,$phoneNumber,$category,$socialid,$token]);
       
        return response()->json([
               'message' => 'Data Insert Successfully'
                ]);
    }
}  
    
  public function signin(Request $request)
  {
    $email = $request->input('email');
    $password = $request->input('password');

    $show = DB::select('select * from user where email=? and password=?',[$email,$password]);
      if(count($show))
          {
                 /* return response()->json([
                        'responcecode' => '2002',
                       'message' => 'signin Success ',
                        'userprofile' =>  $show
                    ]);*/
                    $data=json_encode($show);
                    echo "$data";
                    

      }
    else
       {
          return response()->json([
               'message' => 'No Record Found',
               'responcecode' => '000',
                ]);
       } 
    }

       public function changepassword(Request $request)
       {
        $oldPassword = $request->input('oldPassword');
        $newPassword = $request->input('newPassword');
        $id = $request->input('id');
        //$message='';
        $token = sha1(time());

        $result=DB::select('select * from user where password=? and id=?', [$oldPassword,$id]);
        $message=$result;
        if(count($result))   
        {
          $show=DB::update('update user set password = ? where id = ?',[$newPassword,$id]);
          echo "Password update Successfully";
                
          return response()->json([
              'responcecode' => '200',
               'message' => 'Password Change '

                ]);
        }
        else
        {
          return response()->json([
               'message' => 'No Record Found',
               'responcecode' => '000',
                ]);
          
        }
        }
            public function userprofile(Request $request)
            {
                $id = $request->input('id');
                $token = $request->input('token');
                $show=DB::select('select * from user where id=? and token=?',[$id,$token]);
                if(count($show))
                {
                   return response()->json([
                        'responcecode' => '200',
                       'message' => 'userprofile Success', 
                        'userprofile' => $show
                    ]);
                   
                 }
                else
                {
                    return response()->json([
                     'message' => 'No Record Found',
                     'responcecode' => '000',
                ]);
                }

            }
            public function contactprofile(Request $request)
            {
                $id = $request->input('id');
                $phonenumber = $request->input('phonenumber');
                $token = sha1(time());

                $display=DB::select('select * from user where id=?',[$id]);
                if(count($display))
                {
                    return response()->json([
                        'responcecode' => '200',
                       'message' => 'contactprofile Success', 
                        'contactprofile' => $display
                    ]);
                }
                else
                {
                 return response()->json([
                 'message' => 'No Record Found',
                 'responcecode' => '000',
                ]);               
             }

            }
            
            public function facebooklogin(Request $request)
            {
              $firstname = $request->input('firstname');
              $lastname = $request->input('lastname');
              $email = $request->input('email');
              $socialid = $request->input('socialid');
              $token=sha1(time());


              $show=DB::select('select * from user where email=? and social_id=?',[$email,$socialid]);
              if(count($show))
                {
                $result = DB::update('update user set token=? where social_id=?',[$token,$socialid]);
                
                return response()->json([
                  'responcecode' => '200',
                  'message' => 'Login successfully',
                  'facebookLogin' => $show

                ]);
                
              }
              else
              {
                  $show=DB::insert('insert into user(first_name,last_name,email,social_id,token) values (?,?,?,?,?)', [$firstname,$lastname,$email,$socialid,$token]);
                  return response()->json([
                    'responcecode' => '200',
                    'message' => 'New data insert'
                  ]);
              }
            }
                
         }
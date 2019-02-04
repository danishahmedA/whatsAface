<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use DB;  
use Mail;

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
        $password=md5($password);
         $requestId = $request->input('requestId');
         $show=DB::select('select * from user where email=? or phone_number=?',[$email,$phoneNumber]);
              if(count($show))
                {     
                      if($show[0]->email==$email)
                      {
                      return response()->json([
                     'responseCode'=> '000',
                     'responseMessage' => 'Email already exists',
                      'requestId'=>   $requestId 
                     ]);
                    }
                    else if($show[0]->phone_number==$phoneNumber)
                    {
                      return response()->json([
                     'responseCode'=> '000',
                     'responseMessage' => 'Phone number already exists',
                      'requestId'=>   $requestId 
                     ]);
                    }
                }
        $token=sha1(time());
          $file_data = $request->input('image_file');
            $file_name="";
           if ($file_data != "") {
          $file_name = 'image_' . time() . '.png'; //generating unique file name;
          // storing image in storage/app/public Folder
          Storage::disk('public')->put($file_name, base64_decode($file_data));
      }

      //DB::insert('insert into user(first_name,last_name,email,password,phone_number,category,image,token) values(?,?,?,?,?,?,?,?)',
       // [$firstName,$lastName,$email,$password,$phoneNumber,$category,$file_name,$token]);

       $id = DB::table('user')->insertGetId([
          'first_name'  => $firstName,
          'last_name'  => $lastName,
          'password'  => $password,
          'phone_number'  => $phoneNumber,
          'category'  => $category,
          'image'  => $file_name,
          'token'  => $token, 
          'email' => $email
      ]);

         $categoryData = array(
    array('category_user'=>$id, 'category_name'=> 'Family'),
    array('category_user'=>$id, 'category_name'=> 'Friends'),
    array('category_user'=>$id, 'category_name'=> 'Sporting Events'),
     array('category_user'=>$id, 'category_name'=> 'Builder'),
      array('category_user'=>$id, 'category_name'=> 'Plumber'),
       array('category_user'=>$id, 'category_name'=> 'Service'),
        array('category_user'=>$id, 'category_name'=> 'Doctor'),
        array('category_user'=>$id, 'category_name'=> 'Dentist'),
        array('category_user'=>$id, 'category_name'=> 'Veterinary')
    //...
      );
         DB::table('category')->insert($categoryData);
        return response()->json([
        	   'responseCode'=> '200',
               'message' => 'Data Insert Successfully',
                'requestId'=>   $requestId 
                ]);
    }  

    
	public function signin(Request $request)
	{
		$email = $request->input('email');
		$password = $request->input('password');
    $password=md5($password);
     $requestId = $request->input('requestId');
		$user=DB::select('select * from user where email=? and password=?',[$email,$password]);
		if(count($user))
		{   
                 $token=sha1(time());  
                 $result = DB::update('update user set token=? where id=?',[$token,$user[0]->id]);
                // $show=DB::update('update user set token = ? where id = ?',[$token,$user[0]->id]);

                        $baseUrl='http://13.58.39.254/storage/'; 
	

			return response()->json([
                        'responseCode'=> '200',
                        'message' => 'Login Success',
                        'userId'=>$user[0]->id,
                        'firstName' =>   $user[0]->first_name,
                         'lastName' =>   $user[0]->last_name,
                           'email' =>   $user[0]->email,
                            'image' =>   $baseUrl.$user[0]->image,
                              
                            'phone_number' =>   $user[0]->phone_number,
                             'token' =>   $token,
                              'requestId'=>   $requestId 
                    ]);

		}
		else
   		 {
      		return response()->json([
                     'responseCode'=> '000', 
               'message' => 'No Record Found',
                'requestId'=>   $requestId 
                ]);
    	 } 
    }
          public function sendPickupRequest(Request $request)
   {
         $pickupArea = $request->input('pickupArea');
      $destination = $request->input('destination');
      $requestNumber = "123";
       $userId = $request->input('userId');
     return response()->json(["pickupArea" => $pickupArea,"destination" => $destination,"requestNumber" => $requestNumber,"userId" => $userId]);
      
      //return response()->json('pickupArea' =>$pickupArea,'destination'=>$destination);
   }
    public function pickupRequestResponse(Request $request)
   {
         $answer = $request->input('answer');
         $driverId=$request->input('driverId');
          $requestNumber=$request->input('requestNumber');
         if($answer=='yes')
         {
             $result=DB::select('select * from user where password=? and id=?', [$oldPassword,$id]);
         }
     return response()->json(["pickupArea" => $pickupArea,"destination" => $destination]);
     
   }
    	 public function changepassword(Request $request)
    	 {
    	 	$oldPassword = $request->input('oldPassword');
    	 	$newPassword = $request->input('newPassword');
        $requestId = $request->input('requestId');
    	 	$id = $request->input('userId');
    	 	$message='';
    	 	$token = sha1(time());
        $oldPassword=md5($oldPassword);
        $newPassword=md5($newPassword);
    	 	$result=DB::select('select * from user where password=? and id=?', [$oldPassword,$id]);
    		$message=$result;
    		if(count($result))	 
    	 	{
 	  	 		$show=DB::update('update user set password = ? where id = ?',[$newPassword,$id]);
    	 	
    	 			 		
    			return response()->json([
              'responseCode' => '200',
               'message' => 'Password Changed ',
               'requestId' => $requestId
                ]);
    	 	}
    	 	else
    	 	{
    	 		return response()->json([
              'responseCode' => '000',
               'message' => 'Password did not match',
                'requestId' => $requestId
                ]);
    	 		
    	 	}
        }
            public function userprofile(Request $request)
            {
                $id = $request->input('userId');
                $token = $request->input('token');
                $requestId=$request->input('requestId');
                $user=DB::select('select * from user where id=?',[$id]);

                if(count($user))
                {   
                  $baseUrl='http://13.58.39.254/storage/'; 
                    $image="";
                    if ( $user[0]->image != "")
                    {  
                      $image=$baseUrl.$user[0]->image;

                    }
                  
                       return response()->json([
                        'responseCode'=> '200',
                        'message' => 'Login Success',
                        'userId'=>$user[0]->id,
                        'firstName' =>   $user[0]->first_name,
                         'lastName' =>   $user[0]->last_name,
                           'email' =>   $user[0]->email,
                           'requestId' =>   $requestId,
                            'image' => $image,
                            'phone_number' =>   $user[0]->phone_number,
                            'description' =>   $user[0]->description,
                             'profile' =>   $user[0]->profile,
                              'requestId'=>   $requestId 
                    ]);
                    

                }
                else
                {
                    return response()->json([
                       'responseCode'=> '200',
                       'requestId' =>   $requestId,
                     'message' => 'No Record Found'
                ]);
                }

            }
            public function contactprofile(Request $request)
            {
                $id = $request->input('id');
                $phonenumber = $request->input('phonenumber');
                $token = sha1(time());
                 $requestId = $request->input('requestId');
                $display=DB::select('select * from user where id=?',[$id]);
                if(count($display))
                {
                    return response()->json([
                        'message' => 'data fetched successfully', 
                        'responceCode' => '200',
                        'contactprofile' => $display,
                         'requestId'=>   $requestId 
                    ]);
                }
                else
                {
                 return response()->json([
                 'message' => 'No Record Found',
                 'responceCode' => '000',
                  'requestId'=>   $requestId 
                ]);                }

            }
            public function editProfile(Request $request)
            {  
            
                $firstname = $request->input('first_name');
                $lastname = $request->input('last_name');
                $email = $request->input('email');
                
                $phonenumber = $request->input('phone_number');
               $description=$request->input('description');
                $profile=$request->input('profile');
                 $userId=$request->input('userId');
                $requestId = $request->input('requestId');
               $file_data = $request->input('image_file');
               $file_name="";
               

               if ($file_data != "") {
               $file_name = 'image_' . time() . '.png'; //generating unique file name;
               // storing image in storage/app/public Folder
               Storage::disk('public')->put($file_name, base64_decode($file_data));
                $show = DB::update('update user set first_name=? , last_name=?  ,email=?,image=?, profile=? ,description=?,phone_number=? where id=?',[$firstname,$lastname,$email,$file_name,$profile,$description,$phonenumber,$userId]); 
               } 
               else
                {
                 $show = DB::update('update user set first_name=? , last_name=?  ,email=?, profile=? ,description=?,phone_number=? where id=?',[$firstname,$lastname,$email,$profile,$description,$phonenumber,$userId]); 
                }
             
                   if($show)
                   {
                   return response()->json([
                    'message' => 'data edit Successfully', 
                    'responseCode' => '200',
                    'editcontact' => $show,
                     'requestId'=>   $requestId 

                  ]);
                 }
                 else
                 {
                   return response()->json([
                    'message' => 'Insufficient data', 
                    'responseCode' => '000',
                     'requestId'=>   $requestId 

                  ]);
                 }
            }




            public function forgotPassword(Request $request)
            {
                $email = $request->input('email');
                 $requestId = $request->input('requestId');
                 $check=DB::select('select * from user where email=?',[$email]);

                 if(count($check))
                {     
                  $randomNum=substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyzABCDEFGHIJKLMNOPQRSTVWXYZ"), 0,5);

                  $newPassword=md5($randomNum);

                 $data = array('name'=>$check[0]->first_name,'password'=>$randomNum,'to'=>$check[0]->email);

                 $result = DB::update('update user set password=? where id=?',[$newPassword,$check[0]->id]); 
                 $to=$check[0]->email;
                 Mail::send(['text'=>'mail'], $data, function($message) use ($to) {
                 $message->to($to)->subject
                 ('Account password');
                  $message->from('whatsaface@gmail.com','New password request');
                    });


               return response()->json([
                        'responseCode'=> '200',
                        'message' => 'Password sent to mail', 
                        'requestId'=>   $requestId 
                        
                    ]);
                  
                }
                else
                {
                   return response()->json([
                        'responseCode'=> '200',
                        'message' => 'User does not exist',
                         'requestId'=>   $requestId 
                        
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
               $requestId = $request->input('requestId');

              $show=DB::select('select * from user where email=? and social_id=?',[$email,$socialid]);
              if(count($show))
                {
                $result = DB::update('update user set token=? where social_id=?',[$token,$socialid]);
                
               
                 
            return response()->json([
                        'responseCode'=> '200',
                        'message' => 'Login Success',
                        'userId'=>$show[0]->id,
                        'firstName' =>   $show[0]->first_name,
                         'lastName' =>   $show[0]->last_name,
                           'email' =>   $show[0]->email,
                            'phone_number' =>   $show[0]->phone_number,
                             'token' =>   $token,
                             'requestId'=>   $requestId 
                    ]);

              }
              else
              {
                  $show=DB::insert('insert into user(first_name,last_name,email,social_id,token) values (?,?,?,?,?)', [$firstname,$lastname,$email,$socialid,$token]);
               $lastid = DB::table('user')->latest('id')->first();
                 $categoryData = array(
               array('category_user'=>$lastid->id, 'category_name'=> 'Family'),
               array('category_user'=>$lastid->id, 'category_name'=> 'Friends'),
               array('category_user'=>$lastid->id, 'category_name'=> 'Sporting Events'),
               array('category_user'=>$lastid->id, 'category_name'=> 'Builder'),
               array('category_user'=>$lastid->id, 'category_name'=> 'Plumber'),
               array('category_user'=>$lastid->id, 'category_name'=> 'Service'),
               array('category_user'=>$lastid->id, 'category_name'=> 'Doctor'),
               array('category_user'=>$lastid->id, 'category_name'=> 'Dentist'),
               array('category_user'=>$lastid->id, 'category_name'=> 'Veterinary')
    //...
      );

        // DB::table('category')->insert($categoryData);
                   $user=DB::select('select * from user where id=?',[$lastid->id]);
                   DB::table('category')->insert($categoryData);
                      if(count($user))
                      {   
                        return response()->json([
                        'responseCode'=> '200',
                        'message' => 'Login Success',
                        'userId'=>$user[0]->id,
                        'firstName' =>   $user[0]->first_name,
                         'lastName' =>   $user[0]->last_name,
                           'email' =>   $user[0]->email,
                            'phone_number' =>   $user[0]->phone_number,
                             'token' =>   $token,
                               'requestId'=>   $requestId 
                    ]);

                    }
                   
                 
                  
              }
            }
                
         }
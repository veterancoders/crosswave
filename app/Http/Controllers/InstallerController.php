<?php

namespace App\Http\Controllers;

use App\Models\User;
use Brotzka\DotenvEditor\DotenvEditor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class InstallerController extends Controller
{
     //Returns the installation page
   public function installer()
   {

      return view('installer.welcome');
   }
   //Check system requirement page
   public function Checkrequirement()
   {

      return view('installer.Checkrequirement');
   }

   public function license()
   {

      return view('installer.lisense');
   }
   //Verification action
   public function verification(Request $request)
   {
      return redirect()->route('Databasesetup');
   }

   //DATABASE SETUP AND CODE INSTALLTION
   public function Databasesetup()
   {

      return view('installer.database');
   }
   public function process_database(Request $request)
   {

      $request->validate([
         'database_name' => 'required',
         'database_username' => 'required',
         'database_port' => 'required',
      ]);


      $env = new DotenvEditor();

      $env->changeEnv([
         'DB_DATABASE'   => $request->database_name,
         'DB_USERNAME'   => $request->database_username,
         'DB_PORT'   => $request->database_port,
      ]);

      return redirect()->route('install');
   }
   public function install()
   {
      if (empty(env('DB_DATABASE'))) {

         return redirect()->route('Databasesetup');
      }

      return view('installer.install');
   }


   public function adminsetup()
   {
      if (empty(env('DB_DATABASE'))) {

         return redirect()->route('Databasesetup');
      }


      return view('installer.adminsetup');
   }
   //ADMIN SETUP

   public function admin_setup(Request $request)
   {


      $rules = [
         'admin_email' => 'required',
         'admin_password' => 'required',
         'confirmpassword' => 'required',
      ];

      $validator = Validator::make($request->all(), $rules);

      $validator->after(function ($validator) {

         $password = request('admin_password');

         $password_confirmation = request('confirmpassword');

         if ($password_confirmation != $password) {

            $validator->errors()->add('password_confirmation', 'The password Confirmation does not match');
         }
      });

      $admin = User::first();
      if ($validator->fails()) {
         return redirect()->back()->withErrors($validator);
      }

      $admin->email = request('admin_email');
      $admin->password = Hash::make(request('admin_password'));
      $admin->save();

      return redirect()->route('done');
   }



   public function process_db(Request $request)
   {

      try {
         $new_db_name =  env('DB_DATABASE');
         $new_mysql_username = env('DB_USERNAME');
         $new_mysql_password =  env('DB_PASSWORD');

         $conn = mysqli_connect(
            config('database.connections.mysql.host'),
            env('DB_USERNAME'),
            env('DB_PASSWORD')
         );
         if (!$conn) {
            return false;
         }
         $sql = 'CREATE Database IF NOT EXISTS ' . $new_db_name;
         $exec_query = mysqli_query($conn, $sql);
         if (!$exec_query) {
            die('Could not create database: ' . mysqli_error($conn));
         }
         ini_set('memory_limit', '-1');

         DB::connection('mysql')->unprepared(file_get_contents(storage_path('investpromain.sql')));


         return redirect()->route('adminsetup')->with('success', 'Installed successfully');
      } catch (\Exception $e) {
         return false;
      }
   }
   public function done()
   {

      return view('installer.done');
   }
}

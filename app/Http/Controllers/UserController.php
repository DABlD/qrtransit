<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{User, AuditTrail};
use DB;
use Auth;

class UserController extends Controller
{
    public function get(Request $req){
        $array = DB::table('users')->select($req->select);
        $array = $array->where('deleted_at', null);

        // IF HAS SORT PARAMETER $ORDER
        if($req->order){
            $array = $array->orderBy($req->order[0], $req->order[1]);
        }

        // IF HAS WHERE
        if($req->where){
            $array = $array->where($req->where[0], isset($req->where[2]) ? $req->where[1] : "=", $req->where[2] ?? $req->where[1]);
        }

        // IF HAS WHERE2
        if($req->where2){
            $array = $array->where($req->where2[0], isset($req->where2[2]) ? $req->where2[1] : "=", $req->where2[2] ?? $req->where2[1]);
        }

        // IF HAS JOIN
        if($req->join){
            $alias = substr($req->join, 1);
            $array = $array->join("$req->join as $alias", "$alias.fid", '=', 'users.id');
        }

        $array = $array->get();

        // IF HAS LOAD
        if($array->count() && $req->load){
            foreach($req->load as $table){
                $array->load($table);
            }
        }

        // IF HAS GROUP
        if($req->group){
            $array = $array->groupBy($req->group);
        }

        echo json_encode($array);
    }

    public function store(Request $req){
        $data = new User();
        $data->username = $req->username;
        $data->fname = $req->fname;
        $data->mname = $req->mname;
        $data->lname = $req->lname;
        $data->role = $req->role;
        $data->email = $req->email;
        $data->birthday = $req->birthday;
        $data->gender = $req->gender;
        $data->address = $req->address;
        $data->contact = $req->contact;
        $data->password = $req->password;

        $data->save();
        $this->log(auth()->user()->fullname, 'Create User', "Device ID: " . $data->id);
    }

    public function update(Request $req){
        $update = DB::table('users')->where('id', $req->id)->update($req->except(['id', '_token']));
        $this->log(auth()->user()->fullname, 'Updated User', "ID: $req->id");
    }

    public function updatePassword(Request $req){
        $user = User::find(auth()->user()->id);
        $user->password = $req->password;
        $this->log(auth()->user()->fullname, 'Updated Password', "---");
        $user->save();
    }

    public function delete(Request $req){
        User::find($req->id)->delete();
        $this->log(auth()->user()->fullname, 'Delete User', "ID: $req->id");
    }

    public function index(){
        return $this->_view('index', [
            'title' => 'Users'
        ]);
    }

    private function _view($view, $data = array()){
        return view('users.' . $view, $data);
    }

    public function log($user, $action, $description){
        $data = new AuditTrail();
        $data->uid = $user;
        $data->action = $action;
        $data->description = $description;
        $data->save();
    }
}

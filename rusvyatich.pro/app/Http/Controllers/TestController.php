<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DeviceInformation;
use App\DeviceUser;

class TestController extends Controller
{
    public function index()
    {
        return view('test1');
    }

    public function updateUser(request $request)
    {
        $user = DeviceUser::where('uuid', $request->uuid)->first();
        if (!$user) {
            $user = new DeviceUser();
            $user->uuid = $request->uuid;
            $user->user_agent = $_SERVER['HTTP_USER_AGENT'];
            $user->count_visit = 1;
            $user->save();
        } else {
            $user->last_visit = date('Y-m-d H:i:s');
            $user->increment('count_visit');
            $use->save();
        }
        return response()->json(['success'=>true]);
    }

    public function testSave(request $request)
    {
        $user = DeviceUser::where('uuid', $request->uuid)->first();
        if (!$user) {
            return response()->json(['success'=>false]);
        } else {
            $user->last_visit = date('Y-m-d H:i:s');
            $user->save();
        }
        $info = new DeviceInformation();
        $info->user_id = $user->id;
        $info->orientation = $request->orientation;
        $info->light = $request->light;
        $info->battery = $request->battery;
        $info->save();
        return response()->json(['success'=>true]);
    }
    public function dropUser($id)
    {
        DeviceInformation::where('user_id', $id)->delete();
        DeviceUser::where('id', $id)->delete();
        return redirect()->route('showUsers');
    }

    public function showUsers()
    {
        $users = DeviceUser::all();
        return view('listUser')->with(['users'=>$users]);
    }

    public function showUser($id)
    {
        $user = DeviceUser::find($id);
        $informations = DeviceInformation::where('user_id', $id)->get();
        return view('userDevice')->with(['user' => $user, 'informations' => $informations]);
    }
}

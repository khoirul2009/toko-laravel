<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function getAdmin(Request $request)
    {
        if ($request->ajax()) {
            $data = User::where('role', 1)->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }
    public function getCommonUser(Request $request)
    {
        if ($request->ajax()) {
            $data = User::where('role', 2)->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }
}

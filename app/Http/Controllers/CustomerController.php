<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Customer;
use App\Models\Customer as ModelsCustomer;
use PDO;

class CustomerController extends Controller
{
    //
    public function index(){
        $customer = ModelsCustomer::all();
        $data = ['customer' => $customer];
        return $data;
    }

    public function create(Request $request){
        $customer = new ModelsCustomer();
        $customer->name = $request->name;
        $customer->email = $request->email;
        $customer->password = $request->password;
        $customer->save();

        return " Data disimpan ";
        
    }

    public function update(Request $request, $id){
        $customer = ModelsCustomer::find($id);
        $customer->name = $request->name;
        $customer->email = $request->email;
        $customer->password = $request->password;
        $customer->save();

        return " Data sudah diubah ";
    }

    public function delete($id){
        $customer = ModelsCustomer::find($id);
        $customer->delete();
        return " Data sudah dihapus";
    }

    public function detail($id){
        $customer = ModelsCustomer::find($id);
        return $customer;
    }
}

<?php
//
//namespace App\Http\Controllers;
//use Illuminate\Http\Request;
//use App\Models\User;
//
//class UserController extends Controller
//{
//    public function create(Request $request)
//    {
//        $user = new User();
//        $user->fill([
//            'first_name' => $request->first_name,
//            'last_name' => $request->last_name,
//            'email' => $request->email,
//            'gender' => $request->gender,
//            'username' => $request->username,
//            'password' => $request->password = bcrypt('password'),
//        ])->save();
//
//        try {
//            return response()->json([
//                'status' => 1,
//                'message' => 'User created successfully',
//            ]);
//        } catch (\Exception $e) {
//            return response()->json(['message' => $e->getMessage()], 500);
//        }
//        //dd($request->all());
//    }
//
//    public function showAll()
//    {
//        return response()->json([
//            'status' => 1,
//            'message' => '+',
//            'users_data' => User::all()
//        ]);
//    }
//
//    public function find($id)
//    {
//        return response()->json([
//            'status' => 1,
//            'message' => '+',
//            'users_data' => User::find($id)
//        ]);
//    }
//
//    public function update(Request $request, $id)
//    {
//        $user = User::find($id);
//        $user->fill([
//            $request->all()
//        ])->update();
//
//        $requestContent = $request->all();
//
//        if (array_key_exists('password', $requestContent)) {
//            $requestContent['password'] = bcrypt(
//                $requestContent['password']);
//        }
//
//        try {
//            return response()->json([
//                'status' => 1,
//                'message' => 'User updated successfully',
//                'user info' => User::all()
//            ]);
//        } catch (\Exception $e) {
//            return response()->json(['message' => $e->getMessage()], 500);
//        }
//    }
//}


namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use http\Message;
use Mockery\Matcher\Any;
use mysql_xdevapi\Exception;

class UserController extends Controller
{
    public function create(Request $request): string
    {
        try {
            $newUser = [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'gender' => $request->gender,
                'password' => bcrypt($request->password),
            ];
            $user = new User();
            $user->fill($newUser);
            $user->save();

            return response()->json([
                'status' => 1,
                'message' => 'User created successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => 'Something went wrong'
            ]);
        }
    }

    public function showAll(): string
    {
        return response()->json([
            'status' => 1,
            'message' => 'Table showed successfully',
            'items' => User::all()
        ]);
    }

    public function find($id): string
    {
        return response()->json([
            'status' => 1,
            'message' => 'FOUND!',
            'items' => User::find($id),
        ]);
    }

    public function update(Request $request, $id): string
    {
        $inputs = $request->all();
        $inputs['password'] = bcrypt($inputs['password']);
        $mail = $inputs['email'];
        $mail = User::where("email", '=', $mail)->first();
        $user = User::find($id);
        if (!isset($user) || isset($mail)) {
            return response()->json([
                'status' => '0',
                'message' => 'An error occurred',
            ]);
        } else {
            $user->fill($inputs);
            $user->update();
            return response()->json([
                'status' => 1,
                'message' => "User #$id updated!",
                'items' => User::find($id),
            ]);
        }
    }

    public function delete($id): string
    {
        $user = User::find($id);
        if (!isset($user)) {
            return response()->json([
                'status' => 0,
                'message' => "User #$id not found!",
            ]);
        }
        $user->delete();
        return response()->json([
            'status' => 1,
            'message' => "User #$id deleted!",
        ]);
    }

}

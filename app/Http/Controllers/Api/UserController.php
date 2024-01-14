<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    use ApiResponse;
    public function my_profile()
    {
        return $this->apiResponse(new UserResource(Auth::user()), 'The user Save', JsonResponse::HTTP_CREATED);
    }
    public function update_profile(UserRequest $request)
    {
        $user = User::find(Auth::user()->id);
        if (!$user) {
            return $this->apiResponse(null, 'The user Not Found', JsonResponse::HTTP_NOT_FOUND);
        }
        $user->fill($request->validated())->update();
        if ($request->hasFile('image')) {
            if ($user->image) {
                $exist = Storage::disk('public')->exists("user/image/{$user->image}");
                if ($exist) {
                    Storage::disk('public')->delete("user/image/{$user->image}");
                }
            }
            $imageName = Str::random() . '.' . $request->image->getClientOriginalExtension();
            Storage::disk('public')->putFileAs('user/image', $request->image, $imageName);
            $user->image = $imageName;
            $user->save();
        }

        if ($user) {
            return $this->apiResponse(new userResource($user), 'The user update', JsonResponse::HTTP_CREATED);
        }
    }
}

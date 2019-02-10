<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

// 以下を追記することでProfile Modelが扱えるようになる
use Illuminate\Support\Facades\Auth;
use App\Profile;
// 以下を追記
use App\ProfileHistory;

// 以下を追記
use Carbon\Carbon;


class ProfileController extends Controller
{
  public function add()
  {
      return view('admin.profile.create');
  }

  public function create(Request $request)
  {
    // Varidationを行う
      // $this->validate($request, Profile::$rules);

      $news = new Profile;
      $form = $request->all();
      var_dump($form);

      unset($form['_token']);
      // データベースに保存する
      $news->fill($form);
      $news->save();

      return redirect('admin/profile/create');
  }


 public function edit(Request $request)
   {
       // Profile Modelからデータを取得する
       $profile = Profile::find(Auth::id());
       if (empty($profile)) {
         abort(404);
       }
       return view('admin.profile.edit', ['profile_form' => $profile]);
   }


 public function update(Request $request)
 {
   $this->validate($request, Profile::$rules);
   $profile = Profile::find($request->id);
   $profile_form = $request->all();
   unset($profile_form['_token']);
   unset($profile_form['remove']);
   $profile->fill($profile_form)->save();

   // 以下を追記
        $history = new ProfileHistory;
        $history->profile_id = $profile->id;
        $history->edited_at = Carbon::now();
        $history->save();

 return redirect('admin/profile/edit');
 }
    //
}

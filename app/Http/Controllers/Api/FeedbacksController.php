<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Feedback;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Mail\ContactSubmitted;

class FeedbacksController extends Controller
{
  public function store(Request $request)
  {
    $this->validate($request, [
      'message' => 'required|max:3000',
    ]);

    $user = $request->user();

    $feedback = Feedback::create([
      'message' => $request->get('message'),
      'user_id' => $user ? $user->id : 1,
    ]);

    return response()->json($feedback, Response::HTTP_CREATED);
  }
}

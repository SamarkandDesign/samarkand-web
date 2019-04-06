<?php

namespace App\Http\Controllers;

use App\Feedback;
use Illuminate\Http\Request;
use App\Mail\ContactSubmitted;

class FeedbacksController extends Controller
{
  public function store(Request $request)
  {
    // if we need to redirect back due to failed validation we need an order ID in session
    if ($request->has('order_id')) {
      $request->session()->put('order_id', $request->get('order_id'));
    }

    $this->validate($request, [
      'message' => 'required|max:255',
    ]);

    // we passed validation so we have no need for the order ID
    $request->session()->forget('order_id');

    $feeback = Feedback::create([
      'message' => $request->get('message'),
      'user_id' => $request->user()->id,
    ]);

    return redirect('/')->with([
      'alert' => 'Thanks for your feedback',
      'alert-class' => 'success',
    ]);
  }
}

<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class StripeController extends Controller
{
    public function index(){
        return view('admin.tasks');
    }
    public function checkout(Request $request)
    {
        // Set your Stripe secret key from the environment
        Stripe::setApiKey(config('stripe.sk'));

        // Get the task ID from the request
        $taskId = $request->input('task_id');

          // Fetch the task details from the database
    $task = Task::find($taskId);
    if (!$task) {
        return response()->json(['error' => 'Task not found'], 404);
    }

        // Create a new session for Stripe checkout
        $session = Session::create([
            'line_items'  => [
                [
                    'price_data' => [
                        'currency'     => 'gbp',
                        'product_data' => [
                            'name' => $task->title, // Dynamically pass the task title
                            'description' => $task->description, // Include the task description (optional)
                        ],
                        'unit_amount'  => 500, // Update with the actual task price if needed
                    ],
                    'quantity'   => 1,
                ],
            ],
            'mode'        => 'payment',
            'success_url' => route('success', ['task_id' => $taskId]),
            'cancel_url'  => route('checkout', ['task_id' => $taskId]),
        ]);

        // Return the session URL as a JSON response for the frontend to handle
        return response()->json([
            'url' => $session->url
        ]);
    }
    // public function success()
    // {
    //     return view('admin.tasks');
    // }
    public function success(Request $request)
{
    // Get the task ID from the request
    $taskId = $request->input('task_id');
    
    // Update the task's payment status to paid
    $task = Task::find($taskId); // Assuming you have a Task model
    if ($task) {
        $task->is_paid = true; // Mark the task as paid
        $task->save(); // Save the updated task
    }

    // Redirect to the tasks page or show a success message
    return redirect()->route('admin.tasks')->with('success', 'Payment successful and task marked as paid!');
}

}

<?php

namespace App\Http\Controllers;

use App\Models\Carrier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\CarrierEmailNotification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class EmailController extends Controller
{
    /**
     * Send emails to recipients dynamically based on module and IDs.
     */


    // Define the sendEmails method
    public function sendEmails(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'subject' => 'required|string',
            'content' => 'required|string',
        ]);

        try {
            $carriers = Carrier::whereIn('id', $validated['ids'])->get();

            foreach ($carriers as $carrier) {
                if ($carrier->advertise_email) {
                    Mail::to($carrier->advertise_email)
                        ->send(new CarrierEmailNotification($carrier, $validated['subject'], $validated['content']));
                }
            }

            return response()->json(['message' => 'Emails sent successfully.']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error sending emails.', 'error' => $e->getMessage()], 500);
        }
    }
    /**
     * Get the model class based on the module name.
     */
    protected function getModelClass($module)
    {
        $models = [
            'carriers' => \App\Models\Carrier::class,
            'customers' => \App\Models\Customer::class,
            'quotes' => \App\Models\Quote::class,
        ];

        return $models[$module] ?? null;
    }

    /**
     * Fetch email contacts dynamically based on the model.
     */
    // Your original getEmails method
    protected function getEmails($modelClass, $ids)
    {
        if (!$modelClass) {
            return collect(); // Return an empty collection if the model is invalid
        }

        // Fetch the carriers or customers with their associated contacts
        return $modelClass::whereIn('id', $ids) // Ensure the carriers are selected by their IDs
            ->with('contacts') // Ensure 'contacts' is eager loaded
            ->get()
            ->flatMap(function ($model) {
                // Flatten all contacts of all carriers and filter out null emails
                return $model->contacts->whereNotNull('email');
            });
    }
}

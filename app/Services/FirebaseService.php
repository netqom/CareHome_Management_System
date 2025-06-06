<?php
namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Exception\Messaging\NotFound;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use App\Models\User;
use App\Models\UserAppInfo;

class FirebaseService
{
    protected $messaging;

    public function __construct()
    {
        $factory = (new Factory)->withServiceAccount(config('firebase.projects.app.credentials.file'));
        $this->messaging = $factory->createMessaging();
    }

    /**
     * Send a notification via FCM.
     *
     * @param string $fcmToken
     * @param string $title
     * @param string $body
     * @return array
     */
    /* public function sendNotification(int $user_id, string $title, string $body)
    {
        try {
            // Retrieve the user by ID
            $user = User::find($user_id);
            
            if (empty($user)) {
              //  throw new \Exception('User not found.');
            }
    
            // Retrieve the app information for the user
            $user_app = $user->appInfo;
            
            if (empty($user_app)) {
               // throw new \Exception('User app info not found.');
            }
    
            // Create the notification
            $notification = Notification::create($title, $body);
            
            // Create the message with the user's FCM token
            $message = CloudMessage::withTarget('token', $user_app->fcm_token)
                ->withNotification($notification);
    
            // Send the message
            //dd($message);
            return $this->messaging->send($message);
    
        } catch (\Kreait\Firebase\Exception\Messaging\NotFound $e) {
            // Log specific Firebase Messaging NotFound exception
            error_log('Firebase Messaging Not Found: ' . $e->getMessage());
            error_log('File: ' . $e->getFile());
            error_log('Line: ' . $e->getLine());
            // You can also rethrow the exception or handle it as needed
            throw $e;
        } catch (\Exception $e) {
            // Log general exceptions
            error_log('Error sending notification: ' . $e->getMessage());
            error_log('File: ' . $e->getFile());
            error_log('Line: ' . $e->getLine());
            // You can also rethrow the exception or handle it as needed
            throw $e;
        }
    } */

    public function sendNotification(int $user_id, string $title, string $body)
    {
        $user = User::find($user_id);
        
        if (!$user) {
            \Log::error('User not found for ID: ' . $user_id);
            return; // Early return if user not found
        }
    
        $user_app = $user->appInfo;
        
        if (!$user_app || empty($user_app->fcm_token)) {
            \Log::error('User app info or FCM token not found for user ID: ' . $user_id);
            return; // Early return if user app info or FCM token not found
        }
    
        $notification = Notification::create($title, $body);
        $message = CloudMessage::withTarget('token', $user_app->fcm_token)
                               ->withNotification($notification);
    
        try {
            $response = $this->messaging->send($message);
            return $response;
        } catch (\Kreait\Firebase\Exception\Messaging\NotFound $e) {
            // Log the exception or handle it as needed
            \Log::error('FCM token not found for user ID: ' . $user_id . ' Token: ' . $user_app->fcm_token);
            // Continue with the next iteration or functionality
        } catch (\Exception $e) {
            // Handle other exceptions
            \Log::error('Error sending FCM notification for user ID: ' . $user_id . ' Message: ' . $e->getMessage());
        }
    }
    
    
}

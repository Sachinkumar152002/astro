<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\{Notification};

class NotificationController extends Controller
{
  public function create($notification){
    $notificationModel = new Notification();
    $notificationModel->user_id = $notification['user_id'];
    $notificationModel->astrologer_id = $notification['astrologer_id'];
    $notificationModel->message = $notification['message'];
    $notificationModel->image_id = $notification['image_id'];
    $notificationModel->link = $notification['link'];
    $notificationModel->app_link = $notification['app_link'];
    $notificationModel->for = $notification['for'];
    $notificationModel->on = $notification['on'];
    $notificationModel->status = $notification['status'];
    $notificationModel->save();
    return true;
  }
}

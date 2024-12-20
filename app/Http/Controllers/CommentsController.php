<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Department;
use App\Models\Ticket;
use App\Notifications\TicketNotification;
use App\Traits\EmailTrait;
use Illuminate\Http\Request;
use App\Mailers\AppMailer;
use Illuminate\Support\Facades\Auth;

class CommentsController extends Controller
{
    use EmailTrait;

    public function postComment(Request $request, AppMailer $mailer)
	{
	    $this->validate($request, [
	        'comment'   => 'required'
	    ]);

	    $ticketId = $request->input('ticket_id');
	    $comment = $request->input('comment');
	    $status = $request->input('status');

        $authUser = Auth::user();

        $comment = Comment::create([
            'ticket_id' => $ticketId,
            'user_id'   => $authUser->id,
            'comment'   => $comment,
        ]);
        $ticket = Ticket::with('department')->find($ticketId);
        if ($status == 'Closed'){
            $ticket->update(['status' => 'Closed']);
        }else{
            $ticket->update(['status' => 'Open']);
        }
        $deptUser = Department::with('user')->findOrFail($ticket->department->id);

        $ticketOwner = $comment->ticket->user;
        $subject = "RE: $ticket->title (Ticket ID: $ticket->ticket_id)";
        $details = ['title' => $subject, 'ticket_id' => $ticket->ticket_id];

        // send mail if the user commenting is not the ticket owner
        if ($comment->ticket->user->id !== $authUser->id) {
            $mailText = $this->commentTemplate($authUser, $ticket,$subject, $comment);
            $mailer->sendEmail($mailText, $ticketOwner->email,$subject);
            // send notification
            $ticketOwner->notify(new TicketNotification($details));
        } else{
            if ($deptUser->user->isNotEmpty()){
                $deptUser->user[0]->notify(new TicketNotification($details));
            }else{
                $authUser->isAdmin()->notify(new TicketNotification($details));
            }
        }

        $notify = updateNotify('Your comment has been submitted');

        return redirect()->back()->with($notify);
	}
}

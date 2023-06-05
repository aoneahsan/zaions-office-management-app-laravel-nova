<?php

namespace App\Zaions\Enums\FPI\Projects;

enum ProjectTransactionStatusEnum: string
{
  case initiated = 'initiated';
  case pendingForPayment = 'pendingForPayment'; // means, user created the transaction, project units have been reserved for the user but the payment is not cleared/confirmed from user side yet, so waiting for that right now.

  case pendingForApproval = 'pendingForApproval'; // means, user cleared the payment and submitted the transaction for admin approval, so the units actually show in his profile

  case approved = 'approved'; // transaction approved and units transferred to the buyer user account.
  case rejected = 'rejected'; // due to some reason transaction got rejected.
  case cancelled = 'cancelled'; // transaction cancelled due to some reason (mainly because user did not clear the payment of is not interested in project at all).

  case processing = 'processing'; // after user put the request for 'admin approval' admin can go to the transaction page and put the status to processing so user will know that the request is processing right now.
}

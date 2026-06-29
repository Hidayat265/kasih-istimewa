<p>Dear {{ $donation->donor_name }},</p>

<p>
Thank you for your generous donation.
Please find your official receipt attached to this email.
</p>

<p>
<strong>Receipt No:</strong> {{ $donation->donation_id }} <br>
<strong>Amount:</strong> RM {{ number_format($donation->amount, 2) }} <br>
<strong>Date:</strong> {{ $donation->created_at->format('d M Y') }}
</p>

<p>
Warm regards,<br>
{{ config('organization.name') }}
</p>

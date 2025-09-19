@component('mail::layout')
{{-- Header --}}
@slot('header')
@component('mail::header', ['url' => config('app.url')])
{{ config('app.name') }}
@endcomponent
@endslot

{{-- Body --}}
# @if($status === 'approved')
✅ Your Business Has Been Approved!
@elseif($status === 'rejected')
❌ Your Business Requires Changes
@else
ℹ️ Your Business Status Has Been Updated
@endif

Hello {{ $business->user->name }},

@if($status === 'approved')
Great news! Your business **{{ $business->business_name }}** has been approved by our team. 
You can now start using all the features available to business owners on our platform.

@if($notes)
**Admin Notes:**  
{{ $notes }}
@endif

[Go to Dashboard]({{ $actionUrl }})

@elseif($status === 'rejected')
We've reviewed your business **{{ $business->business_name }}**, but we need some additional information or changes before we can approve it.

**Reason for Rejection:**  
{{ $notes ?? 'No specific reason was provided.' }}

Please update your business profile with the required information and resubmit for review.

[Update Business Profile]({{ $actionUrl }})

@else
Your business **{{ $business->business_name }}** status has been updated to: **{{ ucfirst($status) }}**

@if($notes)
**Admin Notes:**  
{{ $notes }}
@endif

[View Business Dashboard]({{ $actionUrl }})
@endif

{{-- Subcopy --}}
@isset($subcopy)
@slot('subcopy')
@component('mail::subcopy')
{{ $subcopy }}
@endcomponent
@endslot
@endisset

{{-- Footer --}}
@slot('footer')
@component('mail::footer')
© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.

[Privacy Policy]({{ url('/privacy-policy') }}) | [Terms of Service]({{ url('/terms') }})

If you have any questions, please contact our support team at {{ config('mail.support_email') }}
@endcomponent
@endslot
@endcomponent

<style>
/* Custom styles for the email */
.header {
    background-color: #4f46e5;
    color: white;
    padding: 20px 0;
    text-align: center;
}

.button {
    display: inline-block;
    padding: 10px 20px;
    margin: 15px 0;
    background-color: #4f46e5;
    color: white !important;
    text-decoration: none;
    border-radius: 5px;
    font-weight: bold;
}

.footer {
    background-color: #f3f4f6;
    padding: 20px;
    text-align: center;
    color: #6b7280;
    font-size: 12px;
}

.footer a {
    color: #4f46e5;
    text-decoration: none;
    margin: 0 5px;
}

.content {
    max-width: 600px;
    margin: 0 auto;
    padding: 20px;
    line-height: 1.6;
    color: #374151;
}

h1 {
    color: #1f2937;
    margin-bottom: 20px;
}

p {
    margin-bottom: 15px;
}

.note {
    background-color: #f3f4f6;
    border-left: 4px solid #4f46e5;
    padding: 10px 15px;
    margin: 15px 0;
}
</style>

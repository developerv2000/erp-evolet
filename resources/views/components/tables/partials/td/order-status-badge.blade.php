@props(['status'])

<span @class([
    'badge',
    'badge--grey' => $status == App\Models\Order::STATUS_CREATED_NAME,
    'badge--pink' => $status == App\Models\Order::STATUS_IS_SENT_TO_BDM_NAME,
    'badge--yellow' => $status == App\Models\Order::STATUS_IS_SENT_TO_CONFIRMATION_NAME,
    'badge--green' => $status == App\Models\Order::STATUS_IS_CONFIRMED_NAME,
    'badge--orange' => $status == App\Models\Order::STATUS_IS_SENT_TO_MANUFACTURER_NAME,
])>
    {{ $status }}
</span>
